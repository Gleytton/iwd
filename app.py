import lxml.etree as ET
import pymysql
from datetime import datetime
import argparse
from pymysqlconnector import connection_db, insert_data

# Configurar argumentos de linha de comando
parser = argparse.ArgumentParser(description='Processa o XML e conecta com o banco de dados.')
parser.add_argument('xml_file', help='Nome do arquivo XML de entrada')
args = parser.parse_args()

try:
    # Carregar o arquivo XML
    tree = ET.parse(args.xml_file)
    root = tree.getroot()
except ET.XMLSyntaxError as e:
    print(f"Erro de sintaxe XML: {e}")
    exit(1)
except FileNotFoundError as e:
    print(f"Arquivo não encontrado: {e}")
    exit(1)
except Exception as e:
    print(f"Erro ao carregar o arquivo XML: {e}")
    exit(1)

connection = connection_db()
try:
    with connection.cursor() as cursor:
        # Criação das tabelas
        cursor.execute(""" 
            CREATE TABLE IF NOT EXISTS host (
                id INT AUTO_INCREMENT,
                ip VARCHAR(20) NOT NULL,
                name VARCHAR(100),
                data_scan DATETIME NOT NULL,
                data_db_insertion DATETIME,
                ostype VARCHAR(100),
                PRIMARY KEY (ip, data_scan),
                UNIQUE (id)
            );
        """)
        cursor.execute(""" 
            CREATE TABLE IF NOT EXISTS service (
                id INT AUTO_INCREMENT PRIMARY KEY,
                name VARCHAR(100) NOT NULL,
                product VARCHAR(100),
                version VARCHAR(50),
                extrainfo VARCHAR(255),
                port VARCHAR(10) NOT NULL,
                state VARCHAR(50),
                reason VARCHAR(100),
                protocol VARCHAR(10) NOT NULL
            );
        """)
        cursor.execute(""" 
            CREATE TABLE IF NOT EXISTS host_service (
                id INT AUTO_INCREMENT PRIMARY KEY,
                host_id INT NOT NULL,
                service_id INT NOT NULL,
                FOREIGN KEY (host_id) REFERENCES host(id) ON DELETE CASCADE,
                FOREIGN KEY (service_id) REFERENCES service(id) ON DELETE CASCADE,
                UNIQUE KEY (host_id, service_id)
            );
        """)
        cursor.execute(""" 
            CREATE TABLE IF NOT EXISTS historico (
                id INT AUTO_INCREMENT PRIMARY KEY,
                host_id INT NOT NULL,
                service_id INT NOT NULL,
                data_scan DATETIME NOT NULL,
                data_db_insertion DATETIME,
                old_status VARCHAR(50),
                new_status VARCHAR(50),
                -- change_time DATETIME NOT NULL,
                host_ip VARCHAR(100),
                host_name VARCHAR(255),
                host_os VARCHAR(100),
                service_name VARCHAR(255),
                service_product VARCHAR(255),
                service_version VARCHAR(100),
                service_protocol VARCHAR(50),
                service_port INT,
                service_reason VARCHAR(255),
                FOREIGN KEY (host_id) REFERENCES host(id) ON DELETE CASCADE,
                FOREIGN KEY (service_id) REFERENCES service(id) ON DELETE CASCADE
            );
        """)
        connection.commit()
        print("Tabelas criadas com sucesso.")

except pymysql.MySQLError as e:
    print(f"Erro ao criar as tabelas: {e}")
finally:
    connection.close()

# Processar o XML e inserir/atualizar dados
for host in root.findall('host'):
    try:
        # Extrair informações do host
        address_element = host.find('address')
        address = address_element.get('addr') if address_element is not None else 'Unknown'
        host_name = host.find('hostnames/hostname').get('name') if host.find('hostnames/hostname') is not None else 'Unknown'
        os_element = host.find('os/osmatch')
        os_name = os_element.get('name') if os_element is not None else 'Unknown'
        scan_date = root.attrib.get('startstr', 'Unknown')

        # Converter scan_date para datetime e formatar para o formato desejado
        try:
            scan_date_converted = datetime.strptime(scan_date, '%a %b %d %H:%M:%S %Y')
            scan_date_formatted = scan_date_converted.strftime('%Y-%m-%d %H:%M:%S')
        except ValueError as e:
            print(f"Erro ao converter data_scan: {e}")
            scan_date_formatted = None
        insertion_date = datetime.now().strftime('%Y-%m-%d %H:%M:%S')

        # Preparar dados do host
        host_data = {
            'ip': address,
            'name': host_name,
            'data_db_insertion': insertion_date,
            'data_scan': scan_date_formatted,          
            'ostype': os_name
        }
        print(f"Inserting host data: {host_data}")
        host_id = insert_data('host', host_data)  # Inserir e obter o ID do host
        print(f"Host inserted with ID: {host_id}")  # Verifique o retorno de insert_data

        # Se o host_id não for válido, podemos pular a iteração
        if not host_id:
            print("Erro: ID do host não válido. Pulando host.")
            continue

        # Processar as portas do host
        ports = host.find('ports')
        if ports is not None:
            for port in ports.findall('port'):
                state_element = port.find('state')
                port_id = port.get('portid', 'Unknown')
                protocol = port.get('protocol', 'Unknown')

                state = state_element.get('state') if state_element is not None else 'Unknown'
                reason = state_element.get('reason') if state_element is not None else 'Unknown'

                service_element = port.find('service')
                service_name = service_element.get('name') if service_element is not None else 'Unknown'
                product = service_element.get('product') if service_element is not None else 'Unknown'
                version = service_element.get('version') if service_element is not None else 'Unknown'
                extrainfo = service_element.get('extrainfo') if service_element is not None else 'Unknown'

                # Preparar dados do serviço
                service_data = {
                    'name': service_name,
                    'product': product,
                    'version': version,
                    'extrainfo': extrainfo,
                    'port': port_id,
                    'protocol': protocol,
                    'state': state,
                    'reason': reason
                }

                # Inserir serviço e obter o ID do serviço
                service_id = insert_data('service', service_data)
                print(f"Service inserted with ID: {service_id}")  # Verifique o retorno de insert_data

                # Inserir o relacionamento entre o host e o serviço
                relationship_data = {'host_id': host_id, 'service_id': service_id}
                insert_data('host_service', relationship_data)
                print(f"Relacionameto: {relationship_data}")
                    
    except Exception as e:
        print(f"Erro inesperado ao processar o host: {e}")
        print(f"Host data: {host_data}")  # Exibe os dados do host ao ocorrer o erro
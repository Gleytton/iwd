import pymysql

# Database connection details
database = 'nmapdbtest'
main_table = 'service'  # Nome da tabela principal
history_table = 'historico'  # Nome da tabela de histórico

def connection_db():
    connection = pymysql.connect(
        host='147.65.190.60',
        user='daniel.teixeira',
        password='123mudar',
        database=database
    )
    return connection

def copy_to_history(old_data):
    try:
        connection = connection_db()
        try:
            with connection.cursor() as cursor:
                for row in old_data:
                    cursor.execute(f"INSERT INTO {history_table} (host_ip, serv, port, protocol, os, old_status, new_status, change_time) VALUES (%s, %s, %s, %s, %s, %s, %s, %s)", row)
                connection.commit()
        except pymysql.MySQLError as e:
            print(f"Error executing query: {e}")
        finally:
            connection.close()
    except pymysql.MySQLError as e:
        print(f"Error connecting to the database: {e}")

def update_main_table(new_data):
    try:
        connection = connection_db()
        try:
            with connection.cursor() as cursor:
                cursor.execute(f"UPDATE {main_table} SET status = %s WHERE host = %s", new_data)
                connection.commit()
        except pymysql.MySQLError as e:
            print(f"Error executing query: {e}")
        finally:
            connection.close()
    except pymysql.MySQLError as e:
        print(f"Error connecting to the database: {e}")

def insert_data(table, data):
    connection = connection_db()
    try:
        with connection.cursor() as cursor:
            placeholders = ', '.join(['%s'] * len(data))
            columns = ', '.join(data.keys())
            update_placeholders = ', '.join([f"{col} = VALUES({col})" for col in data.keys()])
            sql = f"INSERT INTO {table} ({columns}) VALUES ({placeholders}) ON DUPLICATE KEY UPDATE {update_placeholders}"
            cursor.execute(sql, list(data.values()))
            connection.commit()
            return cursor.lastrowid
    except pymysql.MySQLError as e:
        print(f"Error inserting data into table {table}: {e}")
        return None
    finally:
        connection.close()

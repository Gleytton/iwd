#!/usr/bin/env bash
set -euo pipefail
IFS=$'\n\t'

# ===== Caminho base (onde o script está) =====
SCRIPT_PATH="$(realpath "$0")"
BASE_DIR="$(dirname "$SCRIPT_PATH")"
SCANS_DIR="$BASE_DIR/scans_done"

# ===== Checagem do parâmetro --apache =====
APACHE_MODE=0
if [[ "${1:-}" == "--apache" ]]; then
  APACHE_MODE=1
  shift
fi

ENDERECO="${1:-}"

# Substitui "/" por "_" para nomes de diretórios/arquivos
SANITIZED_ENDERECO="$(echo "$ENDERECO" | sed 's|/|_|g')"

# ===== Logging =====
LOG_DIR="$BASE_DIR/logs"
mkdir -p "$LOG_DIR"
DATA_HORA="$(date +'%Y%m%d_%H%M%S')"
LOG_FILE="$LOG_DIR/${SANITIZED_ENDERECO}_${DATA_HORA}.log"
exec > >(tee -a "$LOG_FILE") 2>&1

XML_FILE="${SANITIZED_ENDERECO}_${DATA_HORA}.xml"
DEST_DIR="$SCANS_DIR/$SANITIZED_ENDERECO"

# ===== Função para validar endereço IP, CIDR ou domínio =====
validar_endereco() {
  local input="$1"

  # IPv4 ou CIDR IPv4
  if [[ "$input" =~ ^([0-9]{1,3}\.){3}[0-9]{1,3}(/([0-9]|[12][0-9]|3[0-2]))?$ ]]; then
    return 0
  fi

  # IPv6 ou CIDR IPv6 (básico, não cobre todos os casos complexos)
  if [[ "$input" =~ ^([0-9a-fA-F:]+)(/[0-9]{1,3})?$ ]]; then
    return 0
  fi

  # Nome de domínio simples
  if [[ "$input" =~ ^([a-zA-Z0-9.-]+\.[a-zA-Z]{2,})$ ]]; then
    return 0
  fi

  return 1
}

# ===== Permissões =====
if [[ "$APACHE_MODE" -eq 1 ]]; then
  echo "Ajustando permissões para www-data (modo Apache)..."
  chmod 775 "$SCRIPT_PATH" || echo "Aviso: falha ao ajustar permissão do script."
  chmod -R 775 "$BASE_DIR" || echo "Aviso: falha ao ajustar permissões do diretório base."
  chown -R www-data:www-data "$BASE_DIR" || echo "Aviso: falha ao ajustar dono/grupo do diretório base."
else
  echo "Ajustando permissões para o usuário atual..."
  chmod 775 "$SCRIPT_PATH" || echo "Aviso: falha ao ajustar permissão do script."
  chmod -R 775 "$BASE_DIR" || echo "Aviso: falha ao ajustar permissões do diretório base."
  chown -R "$(id -u -n)":"$(id -g -n)" "$BASE_DIR" || echo "Aviso: falha ao ajustar dono/grupo do diretório base."
fi
sleep 1

# ===== Validação do parâmetro =====
if [[ -z "$ENDERECO" ]]; then
  echo "Erro: O endereço de destino não foi fornecido."
  exit 1
fi

echo "Endereço informado: $ENDERECO"

# ===== Validação avançada =====
if ! validar_endereco "$ENDERECO"; then
  echo "Erro: Endereço '$ENDERECO' inválido. Use IPv4, IPv6, CIDR ou domínio válido."
  exit 1
fi
sleep 1

# ===== Criação dos diretórios necessários =====
echo "Criando diretórios..."
mkdir -p "$DEST_DIR"
chmod 775 "$SCANS_DIR" "$DEST_DIR"
mkdir -p "$LOG_DIR"
chmod 775 "$LOG_DIR"
sleep 1

# ===== Execução do scan Nmap =====
echo "Iniciando o scan Nmap para o endereço $ENDERECO..."
nmap -sT -A -T4 -v -oX "$XML_FILE" "$ENDERECO"

# ===== Processamento do XML via Python =====
echo "Executando app.py..."
python3 "$BASE_DIR/app.py" "$XML_FILE"

# ===== Movendo XML processado =====
echo "Movendo o arquivo XML para $DEST_DIR..."
mv "$XML_FILE" "$DEST_DIR/"
sleep 1

# ===== Ajuste final de permissões =====
if [[ "$APACHE_MODE" -eq 1 ]]; then
  echo "Ajustando permissões finais para www-data..."
  chmod 664 "$DEST_DIR/$XML_FILE"
  chown -R www-data:www-data "$BASE_DIR" || echo "Aviso: falha ao ajustar dono/grupo do diretório base."
else
  echo "Ajustando permissões finais para o usuário atual..."
  chmod 664 "$DEST_DIR/$XML_FILE"
  chown -R "$(id -u -n)":"$(id -g -n)" "$BASE_DIR" || echo "Aviso: falha ao ajustar dono/grupo do diretório base."
fi

sleep 1
echo "Script executado com sucesso para $ENDERECO."
FROM python:3.9-slim

# Instalar pacotes necessários
RUN apt-get update && apt-get install -y libssl-dev libffi-dev

# Instalar o pacote cryptography
RUN pip install cryptography

# Instalar dependências do Python
COPY requirements.txt .
RUN pip install --no-cache-dir -r requirements.txt

# Adicionar seu código no container
WORKDIR /app
COPY . /app

# Expor a porta
EXPOSE 5000

# Comando para rodar a aplicação
CMD ["python", "app.py"]

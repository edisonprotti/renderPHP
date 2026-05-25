# Baixa a imagem oficial do PHP com o servidor Apache integrado
FROM php:8.2-apache

# Instala as bibliotecas do sistema necessárias para o PostgreSQL
RUN apt-get update && apt-get install -y libpq-dev

# Instala e ativa o driver PDO do PostgreSQL dentro do PHP
RUN docker-php-ext-configure pgsql -with-pgsql=/usr/local/pgsql \
    && docker-php-ext-install pdo pdo_pgsql pgsql

# Habilita o módulo de reescrita do Apache (boa prática para rotas)
RUN a2enmod rewrite

# Copia os arquivos do seu GitHub para a pasta pública do servidor
COPY . /var/www/html/

# Altera a permissão dos arquivos para o Apache conseguir ler corretamente
RUN chown -R www-data:www-data /var/www/html

# Expõe a porta padrão que o Render usa para o tráfego HTTP
EXPOSE 80

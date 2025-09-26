# Laravel プロジェクト作成用のDockerfile（バージョン固定対応）
FROM php:8.4-cli

# 必要なパッケージ + Docker CLI 依存関係をインストール
RUN apt-get update && apt-get install -y \
    git \
    curl \
    ca-certificates \
    gnupg \
    lsb-release \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    && rm -rf /var/lib/apt/lists/*

# Docker公式リポジトリ追加 & Docker CLI インストール
RUN mkdir -p /usr/share/keyrings \
    && curl -fsSL https://download.docker.com/linux/debian/gpg | gpg --dearmor -o /usr/share/keyrings/docker.gpg \
    && echo "deb [arch=$(dpkg --print-architecture) signed-by=/usr/share/keyrings/docker.gpg] https://download.docker.com/linux/debian $(. /etc/os-release; echo $VERSION_CODENAME) stable" > /etc/apt/sources.list.d/docker.list \
    && apt-get update \
    && apt-get install -y docker-ce-cli \
    && rm -rf /var/lib/apt/lists/*

# PHP拡張機能をインストール
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

# Composerをインストール
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# 作業ディレクトリを設定
WORKDIR /root/src

# Laravelインストーラーをインストール
RUN composer global require laravel/installer

# Laravel Sailもインストール（後でSailを追加する場合に備えて）
RUN composer global require laravel/sail

# パスに追加
ENV PATH="/root/.composer/vendor/bin:${PATH}"

# プロジェクト作成用のスクリプトを作成
RUN echo '#!/bin/bash\n\
if [ -z "$1" ]; then\n\
    echo "使用方法: create-laravel <プロジェクト名> [<バージョン>]"\n\
    echo "例: create-laravel myapp 10.x"\n\
    echo "例: create-laravel myapp 9.x"\n\
    echo "例: create-laravel myapp (最新版)"\n\
    exit 1\n\
fi\n\
\n\
PROJECT_NAME=$1\n\
LARAVEL_VERSION=${2:-""}\n\
\n\
if [ -z "$LARAVEL_VERSION" ]; then\n\
    echo "最新版のLaravelでプロジェクトを作成します..."\n\
    laravel new "$PROJECT_NAME"\n\
else\n\
    echo "Laravel $LARAVEL_VERSION でプロジェクトを作成します..."\n\
    composer create-project laravel/laravel:"$LARAVEL_VERSION.*" "$PROJECT_NAME"\n\
fi\n\
\n\
cd "$PROJECT_NAME"\n\
echo "Sailを追加中..."\n\
composer require laravel/sail --dev\n\
php artisan sail:install\n\
echo "プロジェクト作成完了: $PROJECT_NAME"\n\
' > /usr/local/bin/create-laravel

RUN chmod +x /usr/local/bin/create-laravel

# デフォルトコマンド
CMD ["bash"]

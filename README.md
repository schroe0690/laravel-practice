# laravel-practice

laravel の練習用リポジトリ。

## プロジェクトの作成

sial を使用してのプロジェクトの作成は laravel-practice のルートの Dockerfile を使用して行う。
プロジェクトは projects ディレクトリに作成される。

## 注意点

作成したプロジェクトにおいて、フロントの css, js の読み込みに Vite を用いているものがある。 
通常の sail を使用して立ち上げるコマンドの他に `./vendor/bin/sail npm run dev` を実行すること。


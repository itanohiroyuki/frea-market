# coachtech-flea-market

## 環境構築

**Docker ビルド**

1. `git clone git@github.com:itanohiroyuki/coachtech-flea-market.git
2. cd coachtech-flea-market
3. DockerDesktop アプリを立ち上げる
4. `docker-compose up -d --build`

> _Mac の M1・M2 チップの PC の場合、`no matching manifest for linux/arm64/v8 in the manifest list entries`のメッセージが表示されビルドができないことがあります。
> エラーが発生する場合は、docker-compose.yml ファイルの「mysql」内に「platform」の項目を追加で記載してください_

```bash
mysql:
    platform: linux/x86_64(この文追加)
    image: mysql:8.0.26
    environment:
```

**Laravel 環境構築**

1. `docker-compose exec php bash`
2. `composer install`
3. 「.env.example」ファイルを 「.env」ファイルに命名を変更。または、新しく.env ファイルを作成
4. .env に以下の環境変数を追加

```text
DB_CONNECTION=mysql
DB_HOST=mysql
DB_PORT=3306
DB_DATABASE=laravel_db
DB_USERNAME=laravel_user
DB_PASSWORD=laravel_pass
```

5. アプリケーションキーの作成

```bash
php artisan key:generate
```

6. マイグレーションの実行

```bash
php artisan migrate
```

7. シーディングの実行

```bash
php artisan db:seed
```

8. シンボリックリンク作成

```bash
php artisan storage:link
```

## 使用技術(実行環境)

- PHP8 .4.1
- Laravel 8.83.27
- MariaDB 11.8.3

## テーブル設計

![alt](table_1.png)
![alt](table_2.png)
![alt](table_3.png)
![alt](table_4.png)
![alt](table_5.png)
![alt](table_6.png)
![alt](table_7.png)
![alt](table_8.png)
![alt](table_9.png)

## ER 図

![alt](er.png)

## URL

- 開発環境：http://localhost/
- phpMyAdmin:：http://localhost:8080/

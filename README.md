# 滅火器管理系統
採用原生 JS、HTML、CSS 的滅火器管理系統
## 使用技術
1. HTML5/CSS3
2. JavaScript
3. PHP
4. Mysql
## 安裝專案
1. 下載並安裝 XAMPP、HeidiSQL
2. 把專案放到 XAMPP 目錄的 htdocs 資料夾
3. 開啟 HeidiSQL 並建立資料庫，設定如圖

<p align="center"><img src="Screan/heidiSQL.png" width="400" height="300"></p>

4. 將 Fire_Management.sql 匯入新建的資料庫中
5. 在 XAMPP 控制面板中啟動 Apache 和 Mysql
6. 在 瀏覽器中輸入 localhost/Fire_mesage/
7. 大功告成

## 更改 XAMPP 配置
1. 在 XAMPP 控制面板中選擇 MySQL --> config --> my.ini

<p align="center"><img src="Screan/image-1.png" width="400" height="300"></p>

2. 用記事本開啟 my.ini
3. 將 Mysql Post 改成 3308

<p align="center"><img src="Screan/image-2.png" width="400" height="300"></p>

4. 保存並重新啟動 Mysql

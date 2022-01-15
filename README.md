
可能會用到的指令:
- php artisan scribe:generate (重新創建API 文件) 瀏覽API 文件請至 /docs 查詢
- php artisan route:clear (It clears the config cache, and re-cache it again. It also will clear route cache. and then re-cache routes and also files)
- php artisan route:cache (Serializes the results of your routes. php file--it's performing the operation of parsing the routes once and then storing those result.) 使用Debugbar 必須使用此指令


# 測試EMAIL
php artisan tinker
Mail::raw('Hello World',function($msg){$msg->to('youremail@gmail.com')->subject('Test Email');});


案權限設定
- 專案目錄底下 storage 建議設定 apache 有可讀寫權限(775)
   - chown www-data:www-data -R storage
        - www-data 為 apache 的 deamon name, 在不同的系統下有可能會是不一樣的名稱, 請先從 apache 的 config 中查詢
- 比較不建議但簡易的方式, 將 storage 設定為任何人都有讀寫權限(777)
   - chmod 777 -R storage



# Laravel設定
step 1. 終端機輸入: composer update  //安裝Laravel 所需套件
step 2. 複製.env.example 然後改名成.env並開啟檔案，同步驟 .env.testing.example改成,env.testing

.env 檔案環境變數設定\
APP_URL=http://laravel-breeze.com (依自己的環境填寫)\
APP_DEBUG=true              (預設為True用來除錯用的，請務必在正式區將變數改為false)\
DB_CONNECTION=mysql         (依自己的環境填寫)\
DB_HOST=127.0.0.1           (依自己的環境填寫)\
DB_PORT=3307                (依自己的環境填寫)\
DB_DATABASE=asiayo          (依自己的環境填寫)\
DB_USERNAME=root            (依自己的環境填寫)\
DB_PASSWORD=1234            (依自己的環境填寫)

您可以使用自己的Mail Server 來測試，本範例使用MailTrap

MAIL_MAILER=smtp            (依自己的環境填寫)\
MAIL_HOST=mailhog \
MAIL_HOST=smtp.mailtrap.io  (依自己的環境填寫)\
MAIL_PORT=2525              (依自己的環境填寫)\
MAIL_USERNAME=              (依自己的環境填寫)\
MAIL_PASSWORD=              (依自己的環境填寫)\
MAIL_ENCRYPTION=tls         (依自己的環境填寫)\
MAIL_FROM_ADDRESS=null\
MAIL_FROM_NAME="${APP_NAME}"

Step3 ~ Step8 可以使用php artisan project:init (初始化專案) 、 php artisan project:migrate (重新移植DB ，注意會清除資料)

step 3.終端機輸入: php artisan key:generate (產生網頁為一碼)

step 4.終端機輸入: php artisan migrate (自動更新資料庫)

step 5.終端機輸入: php artisan passport:client --personal (新增金鑰) Personal Access Token

step 6.終端機輸入: php artisan passport:client --password (透過帳號密碼取得金鑰)

step 7.終端機輸入: php artisan passport:keys(建立金鑰)

step 8.終端機輸入: php artisan db:seed (新增測試資料)  |  php artisan migrate:refresh --seed(同時移植Table Schema和建立資料)

step 9.請到自己建立的DB 查看users 資料表，裡面有新建人員帳號 admin@gmail.com，密碼預設為:88888

step 10.請到資料庫的 oauth_clients 資料表，將第二列也就是 name= password_token 這一列的user_id 填入 1 

step 11. 若希望透過postman 或 scribe 來測試API，可事先在網頁以 帳號:admin@gmail.com/密碼:88888 登入，於網頁中按下F12 開啟檢查，在元素 列表中展開 HTML 底下的<head> tag ，找到meta name="api-token" ，並複製content 即可取得Token 金鑰

step 12. 執行 Feature Test 時系統會自動Migrate 和 Seed ，因此必須確認資料庫是否建立且.env.testing 是否設定正確




# Linix 指令:
rm -rf 名稱 (強制刪除資料夾及內容)
cp -R /etc /etc_backup (複製資料夾)
unzip 檔名  (解壓縮)
cat /dev/null > 要清空的檔名+副檔名

# Apache 指令:
sudo systemctl restart apache2.service

# GIT 指令:
從遠端數據庫把專案載下來

新增遠端數據庫
git
git push --set-upstream origin master
Find out which remote branch a local branch is tracking
git branch -vv
新增Branch
git checkout -b feature/自行命名

取得Remote Repository 的指定Branch，然後Checkout
git fetch <remote> <rbranch>:<lbranch>
git checkout <lbranch>
# Git 狀況排除
錯誤描述: Git error on git pull (unable to update local ref):\
錯誤排除: git update-ref -d refs/remotes/origin/[locked branch name]\
比對的是「工作目錄(working tree)」與「索引(index)」之間的差異: git diff\
代表進行「當前的索引狀態(index)」與「當前分支的最新版(repo. HEAD)」比對: git diff --cached HEAD
來 Unstage 所有已列入 Index 的待提交檔案: git reset HEAD~ \
找出所有歷史紀錄: git reflog \
當不必要的檔案已經被推到GitHub 可以透過git rm -r --cached . 來移除並重新add . 和commit\
這個語法代表的是「當前的索引狀態」與「當前分支的最新版」進行比對。這種比對方法，不會去比對「工作目錄」的檔案內容，而是直接去比對「索引」與「目前最新版」之間的差異，這有助於你在執行 git commit 之前找出那些變更的內容，也就是你將會有哪些變更被建立版本的意思: git diff --cached HEAD
git diff => 工作目錄 vs 索引\
git diff HEAD => 工作目錄 vs HEAD\
git diff --cached HEAD => 索引 vs HEAD\
git diff --cached => 索引 vs HEAD\
git diff HEAD^ HEAD => HEAD^ vs HEAD\
# Docker 建置指令
docker-compose up --build 


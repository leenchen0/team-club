# Team Club

仿 Tower，技术栈：vue + CI。数据库系统大作业

## 界面

![登录界面](https://github.com/PencilCl/readme-images/blob/master/team-club/login.png)

![项目选择界面](https://github.com/PencilCl/readme-images/blob/master/team-club/project.png)

![动态界面](https://github.com/PencilCl/readme-images/blob/master/team-club/dynamic.png)

![用户信息界面](https://github.com/PencilCl/readme-images/blob/master/team-club/userinfo.png)

![任务界面](https://github.com/PencilCl/readme-images/blob/master/team-club/task.png)

![任务清单界面](https://github.com/PencilCl/readme-images/blob/master/team-club/taskList.png)

## 安装

### 构建前端 (可跳过)

```
cd FE/team_club
npm i
npm run build
```

### 配置 apache (修改网站根目录)

1. `sudo vim /etc/apache2/apache2.conf`-->修改根目录为项目根目录
2. `sudo vim /etc/apache2/sites-available/000-default.conf`-->找到`DocumentRoot [原根目录]`的位置-->更改为项目根目录。

### 配置数据库

进入 `application/config/database.php`,根据需要修改数据库配置信息

### 导入数据库

进入 `mysql` 后，选择数据库，执行 `source db.sql`,创建表结构及触发器、存储过程。

### 给 static 添加权限
如果上传文件发现 `Permission denied` 问题，需要给 `static` 文件夹添加权限。

```
chmod -R 777 static/
```
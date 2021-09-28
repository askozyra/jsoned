<!-- PROJECT LOGO -->
<br />
<p align="center">
  <h1 align="center">Jsoned</h1>
  <p align="center">
    <img src="https://user-images.githubusercontent.com/72695696/134877789-2c40b86a-e934-44f6-8563-85f4dc219120.png">
  </p>
  <p align="center">
    My first "big" project with REST API
    <br/>
    <br/>
    <br/>
    <a href="https://github.com/askozyra/jsoned/issues">Report Bug</a>
    •
  <a href="https://github.com/askozyra/jsoned/pulls">Request Feature</a>
  </p>
</p>



<!-- TABLE OF CONTENTS -->
<details open="open">
  <summary>Table of Contents</summary>
  <ol>
    <li>
      <a href="#about-the-project">About The Project</a>
      <ul>
        <li><a href="#built-with">Built With</a></li>
      </ul>
    </li>
    <li>
      <a href="#getting-started">Getting Started</a>
      <ul>
        <li><a href="#prerequisites">Prerequisites</a></li>
        <li>
          <a href="#installation">Installation</a>
          <ul>
            <li><a href="#database">Database</a></li>
            <li><a href="#organization">Organization</a></li>
          </ul>
        </li>
      </ul>
    </li>
    <li><a href="#usage">Usage</a></li>
    <li><a href="#contributing">Contributing</a></li>
    <li><a href="#contact">Contact</a></li>
  </ol>
</details>



<!-- ABOUT THE PROJECT -->
## About The Project

![][project-sc]

I found the idea for the project on GitHub as a test task. It was required to create a REST API to work with JSON docs and validate them by JSON-Schema.

It was originally an ordinary one-page text editor, but over time I tried to expand the site's capabilities without planning this work in advance.
So, as a result, it performs its direct job, but there are many "leaky" places that need to be fixed.

The editor's design was taken from [JSON Editor](https://jsoneditoronline.org).

### Built With

* [JSON-Schema Validator](https://github.com/justinrainbow/json-schema/blob/master/README.md)



<!-- GETTING STARTED -->
## Getting Started

Get the project:
  ```sh
  git clone https://github.com/askozyra/jsoned
  ```

### Prerequisites

* [composer](https://getcomposer.org/download/)
* [PHP 8.0](https://www.php.net/releases/8.0/ru.php)
* [MySQL](https://dev.mysql.com/downloads/mysql/)

### Installation

#### Database

1. Create db named `json_db` (tmp step, it will be removed later).

2. Create three tables with prefix which are listed in `config.php`:

  * `PREFIX_users structure:`
  
| NAME | TYPE | ATTRIBUTES | NULL | DEFAULT | EXTRA |
|:-----|:-----|:-----------|:-----|:--------|:------|
| login | varchar(60) || No | None ||
| password | varchar(120) || No | None ||
| description | varchar(255) || Yes | NULL ||
| email | varchar(65) || Yes | NULL ||
| registerAt | datetime || No | CURRENT_TIMESTAMP | DEFAULT_GENERATED |
| lastEntrance | datetime || No | CURRENT_TIMESTAMP | DEFAULT_GENERATED |
| authId | varchar(255) || Yes | NULL ||
| salt | varchar(8) || Yes | NULL ||
| api_token_docs | varchar(256) || Yes | NULL ||
| api_token_users | varchar(256) || Yes | NULL ||

* `PREFIX_user_settings structure:`

| NAME | TYPE | ATTRIBUTES | NULL | DEFAULT | EXTRA |
|:-----|:-----|:-----------|:-----|:--------|:------|
| user_login | varchar(60) || No | None ||
| count_of_docs | int || No | 6 ||
| smoothness_of_anims | tinyint(1) || No | 0 ||

* `PREFIX_documents structure:`

| NAME | TYPE | ATTRIBUTES | NULL | DEFAULT | EXTRA |
|:-----|:-----|:-----------|:-----|:--------|:------|
| id | int | UNSIGNED | No | None | AUTO_INCREMENT |
| title | varchar(100) || No | Document ||
| author | varchar(50) || No | None ||
| visibility | varchar(7) || No | private ||
| payload | json || Yes | NULL ||
| createAt | datetime || No | CURRENT_TIMESTAMP | DEFAULT_GENERATED |
| modifyAt | datetime | on update CURRENT_TIMESTAMP | No | CURRENT_TIMESTAMP | DEFAULT_GENERATED ON UPDATE CURRENT_TIMESTAMP |

3. Create the followings relations between tables:
  
  * `users` & `user_settings`: one-to-one (by login and user_login fields)

![image](https://user-images.githubusercontent.com/72695696/135108547-72fd37f3-f190-48bd-8f65-3b56d0908deb.png)

  * `documents` & `users`: one-to-many (by login and author fields)

![image](https://user-images.githubusercontent.com/72695696/135108633-a691b028-6910-419d-9aa0-f9b4a7446109.png)

#### Organization

1. Check all configs in the file `config.php` and change them if it is necessary.

2. Install composer dependencies:
  ```sh
  composer install
  ```

3. Make sure that you have a valid file structure:
```
├───api
│   ├───private
│   │   ├───posts
│   │   ├───users
│   │   └───user_settings
│   └───public
├───classes
├───css
├───docs
│   ├───api_settings
│   ├───auth
│   │   └───functions
│   ├───drafts
│   │   └───functions
│   ├───editor
│   │   └───functions
│   ├───personal
│   └───posts
├───elems
│   ├───drafts
│   ├───editor
│   └───posts
├───js
│   ├───classes
│   ├───docs
│   └───elems
├───src
└───icons
```



<!-- USAGE EXAMPLES -->
## Usage

Firstly, you need to register new account. Then you can change some settings, generate your own API tokens, create your JSON documents, view the other's people docs
and so on.

<!-- TODO: describe usage of API -->

<!-- CONTRIBUTING -->
## Contributing

1. Fork the Project
2. Create your Feature Branch (`git checkout -b feature/AmazingFeature`)
3. Commit your Changes (`git commit -m 'Add some AmazingFeature'`)
4. Push to the Branch (`git push origin feature/AmazingFeature`)
5. Open a Pull Request



<!-- CONTACT -->
## Contact

Email: khnu2019@gmail.com

LinkedIn: [askozyra](https://linkedin.com/in/askozyra)

Project Link: [https://github.com/askozyra/jsoned][project-url]


<!-- MARKDOWN LINKS & IMAGES -->
[project-url]: https://github.com/askozyra/jsoned
[project-sc]: https://user-images.githubusercontent.com/72695696/135106422-47279b88-dce1-4219-a7f7-6a4f786c8c6b.png
<!--          https://user-images.githubusercontent.com/72695696/134930085-58c66843-a08a-4198-a0a8-3c0ab91d933d.png -->
[project-ico]: https://user-images.githubusercontent.com/72695696/134879629-7c0e7a52-c5b3-484c-b774-82290cf00e9b.png

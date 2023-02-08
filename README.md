# Сервис синхронизации email адресов из AmoCRM в Unisender


Цель учебного проекта, создание сервиса синхронизации email адресов.

Стек: php 7.4,MYSQL 8.0,beanstalkd.

Для создания/изменения схемы базы мы используем миграции phpmig

Для взаимодействия с базой используем ORM Eloquent

Для создания консольных команд Laminas CLI

Реализовано на mezzio.

## Getting Started

Скачайте или выполните команду по клонирование проекта:

```bash
 git clone https://github.com/jaygent/SyncAmoCRM_Unisender
```
Установка composer зависимостей 
```bash
$ composer install
```
Запуск докер контейнеров
```bash
$ docker-compose up -d
```
Запуск миграций
```bash
$ vendor/bin/phpmig migrate
```
### Логика приложения

Контакты синхронизуются при первой установки интеграции в AmoCRM, 
так же модуль выполняет автоматическую подписку на веб-хук.
Дальнейшее добавление,изменение,удаление контактов в AmoCRM, 
приводит к автоматической синхронизации данных в Unisendere.
По дефолту сервис создает в Unisendere список Amo в который и происходит синхронизация.

> ### Консольные команды
> Добавляет в сервис очередей сообщение с текущим временем
> ```bash
> $ vendor/bin/laminas how-time
> ```
> 
> Добавляет в сервис очередей пользователей у которых срок действия токена исткает через N часов
> ```bash
> $ vendor/bin/laminas update-token -t N 
> ```
> 
> Запускает worker для вывода даты и времени из сервиса очередей
> ```bash
> $ vendor/bin/laminas worker:time 
> ```
> Запускает worker для обновления токенов из сервиса очередей
> ```bash
> $ vendor/bin/laminas worker:update-token
> ```
> Так же имеется возможность обновлять токены по cron 
> ```bash
> $ crontab crontab 
> ```

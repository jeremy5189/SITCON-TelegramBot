# SITCON-TelegramBot

## Install

    git clone https://github.com/jeremy5189/SITCON-TelegramBot.git
    cd SITCON-TelegramBot
    cp config.sample.php config.php
    
Make sure that hook.php is pubicly availabe on the web. (e.g. https://example.com/hook.php)

## Create your Telegram Bot

1. Message [http://telegram.me/BotFather](http://telegram.me/BotFather) to create a new bot
2. Get your token, name and write it to ``config.php``

## Generate and watch the log

    php hook.php
    chmod 777 bot.log
    tail -f bot.log

The log will contain timestamp and webhook data from telegram api.

## Set UP webhook

1. Send a post request to ``https://api.telegram.org/bot{TOKEN}/setWebhook?url={YOUR_WEBHOOK_URL}``
2. You must include your server SSL crt file (not private key) in a POST field ``certificate`` (POST type = file)
3. If correct, telegram should response

```
{
    "ok": true,
    "result": true,
    "description": "Webhook was set"
}
```
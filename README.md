# Translation module for Budabot/Nadybot

To install, either
* `git clone` into `extras/TRANSLATE_MODULE` and run `composer update` inside
* Download and extract one of the release zips into `extras/TRANSLATE_MODULE`
* Use `package info TRANSLATE_MODULE` from within the bot

## Usage

To translate any text from language 'from' into language 'to':
`!trans 'from'..'to' 'text to translate'`

To translate any text from language 'from' into English:
`!trans 'from' 'text to translate'`

To translate any text from an auto-detected language into English:
`!trans 'text to translate'`

### Hexlet tests and linter status:
[![Actions Status](https://github.com/Againfacewar/php-project-48/actions/workflows/hexlet-check.yml/badge.svg)](https://github.com/Againfacewar/php-project-48/actions)
[![Maintainability](https://api.codeclimate.com/v1/badges/bbba3caf947d7997956b/maintainability)](https://codeclimate.com/github/Againfacewar/php-project-48/maintainability)
[![Test Coverage](https://api.codeclimate.com/v1/badges/bbba3caf947d7997956b/test_coverage)](https://codeclimate.com/github/Againfacewar/php-project-48/test_coverage)
# Description
This PHP application is designed for comparing JSON and YAML files, functioning similarly to how git diff displays differences between file versions.
## System Requirements
* PHP 8.2 or higher
* Composer (for dependency management)
* Git (for cloning the repository)
* Make utility
## Installation
1. Clone the repository:
```shell
git clone https://github.com/Againfacewar/php-project-48.git
```
2. Navigate to the project directory:
```shell
cd php-project-48
```
3. Install dependencies:
```shell
make install
```
4. Grant execution permissions to the main script:
```shell
sudo chmod +x bin/gendiff
```
## Usage
```shell
bin/gendiff -h
```
## Features
* ```stylish```: Styled format output
* ```plain```: Simple text format
* ```json```: Output in JSON string format
## Usage Examples

### JSON 
[![asciicast](https://asciinema.org/a/tEVjAk5sE55jJYRrx4nmdNfPK.svg)](https://asciinema.org/a/tEVjAk5sE55jJYRrx4nmdNfPK)
### YAML
[![asciicast](https://asciinema.org/a/0ZfdCiQexhfX3Y5j5esBvLkhc.svg)](https://asciinema.org/a/0ZfdCiQexhfX3Y5j5esBvLkhc)
### Stylish
[![asciicast](https://asciinema.org/a/LawcZmJmYicDNNbER9P90V3HW.svg)](https://asciinema.org/a/LawcZmJmYicDNNbER9P90V3HW)
### Plain
[![asciicast](https://asciinema.org/a/uT3Po3AUQV1o9t3WOJvoe1qcc.svg)](https://asciinema.org/a/uT3Po3AUQV1o9t3WOJvoe1qcc)
### Json
[![asciicast](https://asciinema.org/a/37sMqrNEH7m6urWuEYKJfdgQl.svg)](https://asciinema.org/a/37sMqrNEH7m6urWuEYKJfdgQl)
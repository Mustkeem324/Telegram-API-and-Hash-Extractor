# Telegram API and Hash Extractor

This PHP script is designed to extract essential API information (api_id, api_hash, public key) required for accessing Telegram APIs. It also includes functionality for phone number verification and authentication.

## Features

- **API Information Extraction**: Extracts essential API information such as api_id, api_hash, and public key from the Telegram website.
- **Phone Number Verification**: Initiates the phone number verification process required for accessing certain Telegram APIs.
- **Authentication**: Performs authentication using phone numbers and verification codes to retrieve access tokens.

## Installation

1. Clone this repository to your local machine or download the script directly.

2. Upload the script to your server or hosting environment.

3. Ensure that your server supports PHP and has the `curl` extension enabled.

4. If not already done, obtain a Telegram API key from the Telegram website or BotFather.

5. Replace the `$API_KEY` variable with your Telegram API key in the script.

6. Set up your server to handle webhooks if necessary, especially if you plan to use the phone number verification feature.

7. Ensure that your server can send HTTP requests to `https://my.telegram.org/` to extract API information.

## Usage

### Extracting API Information

1. Initiate the bot by sending the `/start` command.

2. Follow the prompts to provide your phone number and verification code.

3. Once authenticated, the bot will automatically extract and display the API information.

### Phone Number Verification

- Upon receiving a phone number, the bot initiates the phone number verification process.

- After entering the verification code, the bot completes the authentication process and proceeds with extracting the API information.

## Contributing

Contributions are welcome! If you have suggestions for improvements or additional features, feel free to open an issue or submit a pull request.

## License

This project is licensed under the [MIT License](LICENSE).

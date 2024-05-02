import json
import logging
import requests
import urllib.parse
import time
import sys
import telebot
from telebot import types
from bs4 import BeautifulSoup
import emoji


TOKEN = 'PLACE YOUR TOKEN HERE'
bot = telebot.TeleBot(TOKEN)


# Configure logging to save errors to a file
logging.basicConfig(filename='error.log', level=logging.ERROR)

def getWeblogin(phone_number):
    try:
        url = "https://my.telegram.org/auth/send_password"

        payload = "phone="+ urllib.parse.quote(phone_number)
        headers = {
        'accept': 'application/json, text/javascript, */*; q=0.01',
        'accept-language': 'en-US,en;q=0.9,ru;q=0.8,zh-TW;q=0.7,zh;q=0.6',
        'content-type': 'application/x-www-form-urlencoded; charset=UTF-8',
        'dnt': '1',
        'origin': 'https://my.telegram.org',
        'referer': 'https://my.telegram.org/auth',
        'sec-ch-ua': '"Google Chrome";v="123", "Not:A-Brand";v="8", "Chromium";v="123"',
        'sec-ch-ua-mobile': '?0',
        'sec-ch-ua-platform': '"Linux"',
        'sec-fetch-dest': 'empty',
        'sec-fetch-mode': 'cors',
        'sec-fetch-site': 'same-origin',
        'user-agent': 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/123.0.0.0 Safari/537.36',
        'x-requested-with': 'XMLHttpRequest'
        }

        response = requests.request("POST", url, headers=headers, data=payload)
        #print(response.text)
        if response.status_code != 200:
            print(f'HTTP error: {response.status_code}')
        else:
            data = json.loads(response.text)
            random_hash = data.get('random_hash')
            if random_hash:
                return [random_hash, phone_number]
            else:
                return [None, None]
    except  Exception as err:
        print(f"error: {err}")
        logging.exception(f"Error : {err}")
        
passwd = None
def authlogin(phone_number):
    global passwd

    try:
        random_hash = getWeblogin(phone_number)
        while passwd is None:
            try:
                time.sleep(4)  # Placeholder for waiting for password input
            except KeyboardInterrupt:
                print('Interrupted, retrying in 30 seconds...')
                time.sleep(30)
        url = "https://my.telegram.org/auth/login"

        payload = f"phone={urllib.parse.quote(random_hash[1])}&random_hash={random_hash[0]}&password={passwd}"
        headers = {
        'accept': 'application/json, text/javascript, */*; q=0.01',
        'accept-language': 'en-US,en;q=0.9,ru;q=0.8,zh-TW;q=0.7,zh;q=0.6',
        'content-type': 'application/x-www-form-urlencoded; charset=UTF-8',
        'dnt': '1',
        'origin': 'https://my.telegram.org',
        'referer': 'https://my.telegram.org/auth',
        'sec-ch-ua': '"Google Chrome";v="123", "Not:A-Brand";v="8", "Chromium";v="123"',
        'sec-ch-ua-mobile': '?0',
        'sec-ch-ua-platform': '"Linux"',
        'sec-fetch-dest': 'empty',
        'sec-fetch-mode': 'cors',
        'sec-fetch-site': 'same-origin',
        'user-agent': 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/123.0.0.0 Safari/537.36',
        'x-requested-with': 'XMLHttpRequest'
        }

        response = requests.request("POST", url, headers=headers, data=payload)
        cookie_dict = response.cookies.get_dict()
        print(cookie_dict)
        if response.status_code != 200:
            print(f'HTTP error: {response.status_code}')
        else:
            cookie_json = json.dumps(cookie_dict)
            data = json.loads(cookie_json)
            stel_token = data.get('stel_token')
            if stel_token:
                return stel_token
            else:
                return None

    except  Exception as err: 
        print(f"error: {err}")
        logging.exception(f"Error : {err}")

def get_api_hash(stel_token):
    headers = {
        'accept': 'text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.7',
        'accept-language': 'en-US,en;q=0.9,ru;q=0.8,zh-TW;q=0.7,zh;q=0.6',
        'cookie': f'stel_token={stel_token}',
        'dnt': '1',
        'referer': 'https://my.telegram.org/',
        'sec-ch-ua': '"Google Chrome";v="123", "Not:A-Brand";v="8", "Chromium";v="123"',
        'sec-ch-ua-mobile': '?0',
        'sec-ch-ua-platform': '"Linux"',
        'sec-fetch-dest': 'document',
        'sec-fetch-mode': 'navigate',
        'sec-fetch-site': 'same-origin',
        'sec-fetch-user': '?1',
        'user-agent': 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/123.0.0.0 Safari/537.36'
    }

    try:
        response = requests.get('https://my.telegram.org/apps', headers=headers)
        response.raise_for_status()  # Raise an exception for bad status codes
        return response.text
    except requests.exceptions.RequestException as e:
        print(f'Request error: {e}')
        return None
    





@bot.message_handler(commands=['start'])
def start_message(message):
    keyboard = types.InlineKeyboardMarkup()
    keyboard.add(types.InlineKeyboardButton("Enter mobile number", callback_data="enter_number"))
    bot.send_message(message.chat.id, "Welcome to the bot!", reply_markup=keyboard)

@bot.callback_query_handler(func=lambda call: call.data == 'enter_number')
def send_number(call):
    bot.edit_message_text(chat_id=call.message.chat.id, message_id=call.message.message_id, text="Please enter your mobile number.")
    bot.register_next_step_handler(call.message, process_phone_number)

def process_phone_number(message):
    chat_id = message.chat.id
    phone_number = message.text
    stel_token = authlogin(phone_number)
    if stel_token:
        bot.send_message(message.chat.id, "Enter passcode:")
        bot.register_next_step_handler(message, process_passcode, stel_token)
    else:
        bot.send_message(message.chat.id, "Authentication failed. Please try again with a valid phone number.")

def process_passcode(message, stel_token):
    chat_id = message.chat.id
    passwd = message.text
    if passwd.strip():
        responseData = get_api_hash(stel_token)
        if responseData:
            soup = BeautifulSoup(responseData, 'html.parser')

            # Find elements by their labels or identifiers
            api_id = soup.find('label', text='App api_id:').find_next_sibling('div').find('strong').text.strip()
            api_hash = soup.find('label', text='App api_hash:').find_next_sibling('div').find('span').text.strip()
            test_config = soup.find('label', text='Test configuration:').find_next_sibling('div').find('strong').text.strip()
            production_config = soup.find('label', text='Production configuration:').find_next_sibling('div').find('strong').text.strip()
            public_key = soup.find_all('code')[-2].text.strip() 

            message = f"{emoji.emojize(':robot_face:', use_aliases=True)} App API ID: {api_id}\n{emoji.emojize(':robot_face:', use_aliases=True)} App API Hash: {api_hash}\n{emoji.emojize(':gear:', use_aliases=True)} Test Configuration: {test_config}\n{emoji.emojize(':gear:', use_aliases=True)} Production Configuration: {production_config}\n{emoji.emojize(':key:', use_aliases=True)} Public Key: {public_key}"
            bot.send_message(chat_id=chat_id, text=message)
        else:
            bot.send_message(message.chat.id, "Authentication failed. Please try again.")

bot.polling()

import sys
import asyncio
import requests
from os import listdir, getcwd
from os.path import isfile, join

url = "http://localhost:12101/api/speech-to-intent?outputFormat=rhasspy"
headers = {'content-type': 'audio/wav'}

class bcolors:
    HEADER = '\033[95m'
    OKBLUE = '\033[94m'
    OKCYAN = '\033[96m'
    OKGREEN = '\033[92m'
    WARNING = '\033[93m'
    FAIL = '\033[91m'
    ENDC = '\033[0m'
    BOLD = '\033[1m'
    UNDERLINE = '\033[4m'

def test_recognition(path, user):
    correct = 0
    wrong = 0
    path += user
    print(f"{bcolors.HEADER}User tested: {user}{bcolors.ENDC}")
    for file in listdir(path):
        if file.endswith(".wav"):
            data = open(path + "/" + file, 'rb')
            r =  requests.post(url, data=data, headers=headers)
            recognized = r.json()["intent"]["name"]
            if (recognized == file.replace(".wav", "")):
                correct += 1
                print(f"{bcolors.OKGREEN}File tested: {file} - Intent recognized: {recognized}{bcolors.ENDC}")
            else:
                wrong += 1
                print(f"{bcolors.FAIL}File tested: {file} - Intent recognized: {recognized}{bcolors.ENDC}")
    total = correct + wrong
    if (total == 0):
        return
    rate = (correct / total) * 100

    if (wrong == 0):
        color = bcolors.OKGREEN
    else:
        color= bcolors.WARNING
    print(f"{color}Recognition rate: {correct}/{total} {rate}%{bcolors.ENDC}")
    return rate

async def main():
    path = getcwd()
    path += "/storage/app/recordings/"
    r = requests.get("http://localhost:12101/api/profile?layers=profile")
    stt = r.json()["speech_to_text"]["system"]
    intent = r.json()["intent"]["system"]
    print(f"{bcolors.HEADER}Speech-to-text system: {stt}{bcolors.ENDC}")
    print(f"{bcolors.HEADER}Intent recognition system: {intent}{bcolors.ENDC}")

    if (len(sys.argv) == 2):
        test_recognition(path, sys.argv[1])
    else:
        for user_dir in listdir(path):
            if (user_dir != ".DS_Store"):
                test_recognition(path, user_dir)

if __name__ == "__main__":
    asyncio.run(main())

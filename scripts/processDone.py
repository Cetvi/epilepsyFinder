import requests

def notifyProcessFinished(userId, projectId):
    url = "http://127.0.0.1:8000/api/process-finished"

    data = {
        "user_id": userId,
        "project_id": projectId,
    }
    
    try:
        response = requests.post(url, json=data)
        if response.status_code == 200:
            print("Notification sent")
        else:
            print(f"Error: {response.status_code} - {response.text}")
    except Exception as e:
        print("Exception", str(e))

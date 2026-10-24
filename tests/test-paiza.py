import urllib.request
import urllib.parse
import json
import time

data = urllib.parse.urlencode({
    'source': '#include <stdio.h>\nint main(){ printf("Hello from Paiza C!\\n"); return 0; }',
    'language': 'c',
    'api_key': 'guest'
}).encode('utf-8')

req = urllib.request.Request('http://api.paiza.io:80/runners/create', data=data)
try:
    with urllib.request.urlopen(req, timeout=10) as r:
        res = json.loads(r.read().decode('utf-8'))
        run_id = res['id']
        print('Paiza runner created, id:', run_id)
        
        # Poll status
        for _ in range(5):
            time.sleep(1)
            status_url = f'http://api.paiza.io:80/runners/get_details?id={run_id}&api_key=guest'
            with urllib.request.urlopen(status_url, timeout=10) as sr:
                s_res = json.loads(sr.read().decode('utf-8'))
                if s_res.get('status') == 'completed':
                    print('Paiza result completed!')
                    print('Stdout:', repr(s_res.get('stdout')))
                    print('Stderr:', repr(s_res.get('stderr')))
                    break
except Exception as e:
    print('Paiza error:', e)

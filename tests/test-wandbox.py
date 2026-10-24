import urllib.request
import json

tests = [
    ('gcc-13.2.0-c', '#include <stdio.h>\nint main(){ printf("Hello C! 42\\n"); return 0; }'),
    ('gcc-13.2.0', '#include <iostream>\nint main(){ std::cout << "Hello C++!\\n"; return 0; }'),
    ('php-8.3.0', '<?php echo "Hello PHP!\\n";'),
    ('rust-1.77.0', 'fn main() { println!("Hello Rust!\\n"); }'),
    ('go-1.22.1', 'package main\nimport "fmt"\nfunc main() { fmt.Println("Hello Go!") }'),
    ('openjdk-jdk-21+35', 'public class Main { public static void main(String[] args) { System.out.println("Hello Java!"); } }')
]

for compiler, code in tests:
    payload = json.dumps({'compiler': compiler, 'code': code}).encode('utf-8')
    req = urllib.request.Request(
        'https://wandbox.org/api/compile.json',
        data=payload,
        headers={'Content-Type': 'application/json', 'User-Agent': 'CodeElta6ur/1.0'}
    )
    try:
        with urllib.request.urlopen(req, timeout=12) as r:
            res = json.loads(r.read().decode('utf-8'))
            out = res.get('program_output', '').strip()
            err = res.get('compiler_error', '').strip()
            status = res.get('status')
            print(f"[{compiler}] -> Status: {status} Output: {repr(out)} Error: {repr(err)}")
    except Exception as e:
        print(f"[{compiler}] -> ERROR: {e}")

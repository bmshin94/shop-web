import os

path = r'c:\Users\SHIN BAEKMIN\.gemini\antigravity\brain\9c34cb30-b545-435c-b623-860bd528c32b\walkthrough.md'

# Read file (could be UTF-16 from PowerShell's Set-Content)
with open(path, 'rb') as f:
    raw = f.read()

# Detect encoding
if raw[:2] in [b'\xff\xfe', b'\xfe\xff']:
    text = raw.decode('utf-16')
else:
    text = raw.decode('utf-8')

# Replace file:/// URL-encoded paths with absolute paths
old = 'file:///C:/Users/SHIN%20BAEKMIN/.gemini/antigravity/brain/9c34cb30-b545-435c-b623-860bd528c32b/'
new = 'C:/Users/SHIN BAEKMIN/.gemini/antigravity/brain/9c34cb30-b545-435c-b623-860bd528c32b/'
text = text.replace(old, new)

# Write back as UTF-8 without BOM
with open(path, 'w', encoding='utf-8', newline='\n') as f:
    f.write(text)

print('Done - file saved as UTF-8')

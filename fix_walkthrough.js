const fs = require('fs');
const path = 'c:/Users/SHIN BAEKMIN/.gemini/antigravity/brain/9c34cb30-b545-435c-b623-860bd528c32b/walkthrough.md';

let raw = fs.readFileSync(path);

// Detect UTF-16 LE BOM
let text;
if (raw[0] === 0xFF && raw[1] === 0xFE) {
  text = raw.toString('utf16le').substring(1); // skip BOM
} else {
  text = raw.toString('utf-8');
  // Remove UTF-8 BOM if present
  if (text.charCodeAt(0) === 0xFEFF) text = text.substring(1);
}

// Replace file:/// URL-encoded paths with simple absolute paths
const oldPath = 'file:///C:/Users/SHIN%20BAEKMIN/.gemini/antigravity/brain/9c34cb30-b545-435c-b623-860bd528c32b/';
const newPath = 'C:/Users/SHIN BAEKMIN/.gemini/antigravity/brain/9c34cb30-b545-435c-b623-860bd528c32b/';

const result = text.split(oldPath).join(newPath);

fs.writeFileSync(path, result, 'utf-8');
console.log('Done - walkthrough.md saved as UTF-8 with fixed image paths');

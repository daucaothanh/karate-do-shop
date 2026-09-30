from zipfile import ZipFile
from xml.etree import ElementTree as ET
from pathlib import Path

path = Path(r'D:\karate_do.1 - Copy\CHUONG_2_KARATE_DO_DAY_DU_HINH_BANG.docx')
if not path.exists():
    raise FileNotFoundError(path)

with ZipFile(path, 'r') as z:
    xml = z.read('word/document.xml')

root = ET.fromstring(xml)
ns = {'w': 'http://schemas.openxmlformats.org/wordprocessingml/2006/main'}
texts = []
for para in root.findall('.//w:p', ns):
    parts = []
    for t in para.findall('.//w:t', ns):
        parts.append(t.text or '')
    text = ''.join(parts)
    if text.strip():
        texts.append(text.strip())

print('\n'.join(texts))
print('---TOTAL PARAS---', len(texts))

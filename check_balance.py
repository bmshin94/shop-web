import sys

def check_balance(filename):
    with open(filename, 'r', encoding='utf-8') as f:
        content = f.read()
    
    stack = []
    lines = content.split('\n')
    for i, line in enumerate(lines):
        # Basic character-by-character check, ignoring strings for now if possible
        # but let's just do a simple one first.
        j = 0
        while j < len(line):
            char = line[j]
            if char in '([{':
                stack.append((char, i + 1, j + 1))
            elif char in ')]}':
                if not stack:
                    print(f"Unexpected {char} at {filename}:{i+1}:{j+1}")
                else:
                    last_char, last_line, last_col = stack.pop()
                    if (char == ']' and last_char != '[') or \
                       (char == ')' and last_char != '(') or \
                       (char == '}' and last_char != '{'):
                        print(f"Mismatched {char} at {filename}:{i+1}:{j+1}, does not match {last_char} at line {last_line}")
            j += 1
            
    for char, line, col in stack:
        print(f"Unclosed {char} from {filename}:{line}:{col}")

if __name__ == "__main__":
    for arg in sys.argv[1:]:
        check_balance(arg)

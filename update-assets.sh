#!/bin/bash
cd /var/www/bulandacanteen

# Read the manifest.json file
MANIFEST="public/build/manifest.json"

# Use Python to parse JSON (Python is usually available)
JS_FILE=$(python3 << END
import json
import sys

try:
    with open("$MANIFEST", "r") as f:
        data = json.load(f)
    
    if "resources/js/app.jsx" in data:
        file_path = data["resources/js/app.jsx"]["file"]
        # Remove "assets/" prefix if present
        if file_path.startswith("assets/"):
            file_path = file_path[7:]
        print(file_path)
    else:
        print("")
except Exception as e:
    print("")
    sys.exit(1)
END
)

if [ -z "$JS_FILE" ]; then
    echo "Error: Could not extract JS filename from manifest"
    exit 1
fi

echo "Found JS file: $JS_FILE"

# Update the Blade file
BLADE_FILE="resources/views/backend/cart/index.blade.php"
if [ -f "$BLADE_FILE" ]; then
    # Create a temporary file for the replacement
    sed "s|app-[a-f0-9]*\.js|$JS_FILE|g" "$BLADE_FILE" > "${BLADE_FILE}.tmp"
    
    # Check if replacement was made
    if grep -q "$JS_FILE" "${BLADE_FILE}.tmp"; then
        mv "${BLADE_FILE}.tmp" "$BLADE_FILE"
        echo "Successfully updated $BLADE_FILE with $JS_FILE"
    else
        rm "${BLADE_FILE}.tmp"
        echo "Warning: No replacement made. Pattern not found."
    fi
else
    echo "Error: Blade file not found: $BLADE_FILE"
    exit 1
fi

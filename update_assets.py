#!/usr/bin/env python3
import json
import re
import os
import sys

def main():
    # Set paths
    base_dir = "/var/www/bulandacanteen"
    manifest_path = os.path.join(base_dir, "public/build/manifest.json")
    blade_path = os.path.join(base_dir, "resources/views/backend/cart/index.blade.php")
    
    # Read manifest
    try:
        with open(manifest_path, 'r') as f:
            manifest = json.load(f)
    except FileNotFoundError:
        print(f"Error: Manifest not found at {manifest_path}")
        return 1
    except json.JSONDecodeError:
        print(f"Error: Invalid JSON in manifest at {manifest_path}")
        return 1
    
    # Get JS file
    if "resources/js/app.jsx" not in manifest:
        print("Error: resources/js/app.jsx not found in manifest")
        return 1
    
    js_file_full = manifest["resources/js/app.jsx"]["file"]
    # Remove assets/ prefix
    js_file = js_file_full.replace("assets/", "")
    
    print(f"Found JS file: {js_file}")
    
    # Read and update Blade file
    try:
        with open(blade_path, 'r') as f:
            content = f.read()
        
        # Replace the old JS filename
        new_content = re.sub(r'app-[a-f0-9]+\.js', js_file, content)
        
        # Write back
        with open(blade_path, 'w') as f:
            f.write(new_content)
        
        print(f"Successfully updated {blade_path}")
        return 0
        
    except FileNotFoundError:
        print(f"Error: Blade file not found at {blade_path}")
        return 1
    except Exception as e:
        print(f"Error updating Blade file: {e}")
        return 1

if __name__ == "__main__":
    sys.exit(main())

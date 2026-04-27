#!/usr/bin/env python3
"""
Update RENT&ROUTE platform for Philippines localization
- Changes locations from US cities to Philippine cities
- Updates currency from USD to PHP (Philippine Peso)
- Updates pricing to reflect Philippine market
"""

import os
import glob
import re

# Configuration
CURRENCY_MAP = {
    '\$25': '₱1,250',
    '\$30': '₱1,500',
    '\$42': '₱2,100',
    '\$50': '₱2,500',
    '\$54': '₱2,700',
    '\$60': '₱3,000',
    '\$75': '₱3,750',
    '\$100': '₱5,000',
    '\$125': '₱6,250',
    '\$160': '₱8,000',
}

LOCATION_MAP = {
    r'Washington': 'Makati',
    r'Newyork, USA': 'Quezon City, Philippines',
    r'New York, USA': 'Manila, Philippines',
    r'Newyork': 'Manila',
    r'Dallas, USA': 'Cebu, Philippines',
    r'Miami St, Destin, FL 32550, USA': 'Ayala Avenue, Makati, Metro Manila, Philippines',
    r'Hollywood, USA': 'Makati, Metro Manila, Philippines',
    r'California, USA': 'Metro Manila, Philippines',
    r'Dreamsrentals USA': 'Dreamsrentals Philippines',
}

def update_file(filepath):
    """Update a single file with Philippines localization"""
    try:
        with open(filepath, 'r', encoding='utf-8') as f:
            content = f.read()
        
        original_content = content
        
        # Update currency
        for usd, php in CURRENCY_MAP.items():
            # Need to properly escape for regex
            pattern = usd.replace('$', r'\$')
            content = re.sub(pattern, php, content)
        
        # Update locations
        for old_loc, new_loc in LOCATION_MAP.items():
            content = content.replace(old_loc, new_loc)
        
        # Only write if content changed
        if content != original_content:
            with open(filepath, 'w', encoding='utf-8') as f:
                f.write(content)
            return True
        return False
    except Exception as e:
        print(f"Error processing {filepath}: {e}")
        return False

# Main execution
if __name__ == '__main__':
    base_dir = os.path.dirname(os.path.abspath(__file__))
    
    # Process all HTML files
    html_files = glob.glob(os.path.join(base_dir, 'html', 'template', '*.html')) + \
                 glob.glob(os.path.join(base_dir, 'html', 'template-rtl', '*.html'))
    
    updated_count = 0
    for filepath in html_files:
        if update_file(filepath):
            print(f"✓ Updated: {os.path.basename(filepath)}")
            updated_count += 1
    
    print(f"\nTotal files updated: {updated_count}")

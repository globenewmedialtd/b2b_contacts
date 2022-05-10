# B2B Contacts

## Installation

Install like any other module.

## Requirements

You need Open Social installed and enabled on your platform.

## Options

To adjust the required group, please use the included hook:
```
function b2b_contacts_b2b_contacts_group_types_alter(&$group_types) {
  $group_types['flexible_group'] = 'flexible_group';
}
```




# Test cases

## Palettes, Subpalettes, Typeselector, concatenated Typeselector behavior

Fields are basically unattached to any `palettes`. By default any fields, can be editable.
Fields, that are part of subpalettes will be rendered strict with their subpalettes.
Permanent fields `formHybridPermanentFields` will be rendered regardless of their belonging to subpalettes. 

### Type selector

Typeselectors in general are `palettes` selectors (e.g. tl_content: default, text, headline…). 
If in `formHybridDefaultValues` a palettes is set or the type selector itself is present and selected, the fields
rendered will be attached to it palettes. Only `formHybridPermanentFields` will be added regardless of their `palettes` belonging.
The `default` palette is the preset, if no type selector is present. Default palette fields are only restricted by their palette,
if `default` palette is set within `formHybridDefaultValues` or the type selector present as editable field.

#### Example

```
$GLOBALS['TL_DCA']['tl_submission']['palettes`]['__selector__'][] = 'type';
$GLOBALS['TL_DCA']['tl_submission']['palettes`]['__selector__'][] = 'isMember';

$GLOBALS['TL_DCA']['tl_submission']['palettes']['default'] = 'firstname,lastname';
$GLOBALS['TL_DCA']['tl_submission']['palettes']['registration'] = 'firstname,lastname,company,email,isMember';
$GLOBALS['TL_DCA']['tl_submission']['palettes']['eventregistration'] = 'firstname,lastname,event,isMember';

$GLOBALS['TL_DCA']['tl_submission']['subpalettes']['isMember_yes'] = 'association,accessionDate';
$GLOBALS['TL_DCA']['tl_submission']['subpalettes']['isMember_no'] = 'association,memberID';
```

1. No active type selector - available fields 

```
firstname,lastname,company,email,event,isMember
```

2. Active type selector `registration` - available fields 

```
firstname,lastname,company,email,isMember
```

If the concatenated type selector for the subpalettes isMember is active, for `isMember_yes`, than the field `memberID` from subpaletette `isMember_no` will be unset.

3. Active type selector `eventregistration`, with permanent field `company` - available fields 

```
firstname,lastname,event,isMember,company
```




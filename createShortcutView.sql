create view shortcutview as
select s.id shortcut_id, s.name name, s.beschreibung beschreibung,
       i.id item_id, i.art_id art, zimmer_id, etagen_id, funkwahl, on_off,
       c.id config_id, c.name name

from homecontrol_shortcut s, 
     homecontrol_shortcut_items i, 
     homecontrol_config c

WHERE i.shortcut_id = s.id
  AND (
                  (c.id = i.config_id OR i.config_id is null)
          AND  (c.control_art = i.art_id OR i.art_id is null)
          AND  (c.zimmer = i.art_id OR i.zimmer_id is null)
          AND  (c.etage = i.art_id OR i.etagen_id is null)
     )
drop view if exists homecontrol_shortcutview;

create view homecontrol_shortcutview as
select concat(s.id,'-', c.id) id,
       s.id shortcut_id, s.name shortcut_name, s.beschreibung beschreibung,
       i.id item_id, i.art_id art, zimmer_id, etagen_id, funkwahl, on_off,
       c.id config_id, c.name name, c.funk_id, c.funk_id2, c.x, c.y, a.pic, c.geaendert geaendert

from homecontrol_shortcut s, 
     homecontrol_shortcut_items i, 
     homecontrol_config c,
     homecontrol_art a

WHERE i.shortcut_id = s.id
  AND (        (c.id = i.config_id OR i.config_id is null)
          AND  (c.control_art = i.art_id OR i.art_id is null)
          AND  (c.zimmer = i.art_id OR i.zimmer_id is null)
          AND  (c.etage = i.art_id OR i.etagen_id is null)
     )
  AND c.control_art = a.id
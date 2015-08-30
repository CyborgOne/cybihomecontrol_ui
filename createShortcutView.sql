drop view if exists homecontrol_shortcutview;
  

create view homecontrol_shortcutview as
select concat(s.id,'-', c.id) id,
       s.id shortcut_id, s.name shortcut_name, s.beschreibung beschreibung,
       i.id item_id, i.art_id art, c.zimmer, z.etage_id, i.funkwahl, i.on_off,
       c.id config_id, c.name name, c.funk_id, c.funk_id2, c.x, c.y, a.pic, c.geaendert geaendert

from homecontrol_shortcut s, 
     homecontrol_shortcut_items i, 
     homecontrol_config c  LEFT JOIN 
     homecontrol_zimmer z ON z.id = c.zimmer  LEFT JOIN 
     homecontrol_art a ON c.control_art = a.id

WHERE i.shortcut_id = s.id
  AND (        (c.id = i.config_id OR i.config_id is null)
          AND  (c.control_art = i.art_id OR i.art_id is null)
          AND  (c.zimmer = i.zimmer_id OR i.zimmer_id is null)
          AND  (c.etage = i.etagen_id OR i.etagen_id is null)
     )

#zimmer nur wenn keine konkrete id
  AND (
    (
        (i.config_id IS NULL AND i.zimmer_id IS NOT NULL)
        AND NOT EXISTS( 
            SELECT 'X' FROM homecontrol_shortcut_items iZ WHERE iZ.shortcut_id = s.id AND iZ.config_id = c.id
        )
    ) OR (i.config_id IS NOT NULL OR i.zimmer_id IS NULL)
  )

#etage nur wenn keine konkrete id oder zimmerangabe
  AND (
    (
        (i.config_id IS NULL AND i.etagen_id IS NOT NULL)
        AND NOT EXISTS( 
            SELECT 'X' FROM homecontrol_shortcut_items iZ WHERE iZ.shortcut_id = s.id AND (iZ.config_id = c.id OR iZ.zimmer_id = i.zimmer_id)
        )
    ) OR (i.config_id IS NOT NULL OR i.zimmer_id IS NOT NULL OR i.etagen_id IS NULL)
  )
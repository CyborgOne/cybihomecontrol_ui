drop view if exists homecontrol_regel_item_view;
  
create view homecontrol_regel_item_view as
SELECT CONCAT( r.id,  '-', i.id ) id, r.id regel_id, r.name name, r.beschreibung beschreibung, i.config_id config_id, i.art_id art_id, i.zimmer_id zimmer_id, i.etagen_id etagen_id, i.on_off on_off
FROM homecontrol_regeln r, homecontrol_regeln_items i
WHERE r.id = i.regel_id


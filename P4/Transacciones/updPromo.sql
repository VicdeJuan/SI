ALTER TABLE customers ADD COLUMN promo numeric;

UPDATE orders SET status = NULL WHERE orderid < 6;

CREATE OR REPLACE FUNCTION updPromo() RETURNS trigger as $updPromo$
BEGIN
	UPDATE orders
		SET netamount = netamount * (100 - NEW.promo) / 100
		WHERE status IS NULL
			AND customerid = NEW.customerid;

	RETURN NEW;
END
$updPromo$ language 'plpgsql';

CREATE TRIGGER updPromo AFTER UPDATE OF promo ON customers
	FOR EACH ROW EXECUTE PROCEDURE updPromo();


-- Añadimos columna promo (ap. b)
ALTER TABLE customers ADD COLUMN promo numeric;

-- Ponemos varios carritos sin comprar (ap. f)
UPDATE orders SET status = NULL WHERE orderid < 6;

-- Creamos el trigger (ap. c)
CREATE OR REPLACE FUNCTION updPromo() RETURNS trigger as $updPromo$
BEGIN
	UPDATE orders
		SET netamount = netamount * (100 - NEW.promo) / 100
		WHERE status IS NULL
			AND customerid = NEW.customerid;

	PERFORM pg_sleep(10);

	RETURN NEW;
END
$updPromo$ language 'plpgsql';

CREATE TRIGGER updPromo AFTER UPDATE OF promo ON customers
	FOR EACH ROW EXECUTE PROCEDURE updPromo();


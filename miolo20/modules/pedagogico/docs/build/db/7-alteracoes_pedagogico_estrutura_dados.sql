ALTER TABLE acprecuperacao DROP COLUMN estadodematriculaaprovacaorecuperacaoid;
ALTER TABLE acprecuperacao DROP COLUMN estadodematriculareprovacaorecuperacaoid;
ALTER TABLE acpmodelodeavaliacao ADD COLUMN estadodematriculaaprovacaorecuperacaoid int REFERENCES acpestadodematricula(estadodematriculaid);
ALTER TABLE acpmodelodeavaliacao ADD COLUMN estadodematriculareprovacaorecuperacaoid int REFERENCES acpestadodematricula(estadodematriculaid);
ALTER TABLE acpcontroledefrequencia DROP COLUMN modelodeavaliacaoid;
ALTER TABLE acpmodelodeavaliacao ADD COLUMN controledefrequenciaid int REFERENCES acpcontroledefrequencia(controledefrequenciaid);
ALTER TABLE acpconceitosdeavaliacao DROP COLUMN modelodeavaliacaoid;
ALTER TABLE acpconceitosdeavaliacao ADD COLUMN conjuntodeconceitosid INT REFERENCES acpconjuntodeconceitos(conjuntodeconceitosid);
ALTER TABLE acpconjuntodeconceitos DROP column conceitodeavaliacaoid;

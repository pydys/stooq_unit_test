


build:
	@docker-compose up --build -d --force-recreate

down:
	@docker-compose down --volumes

app-bash:
	@docker-compose exec app /bin/bash

ccl:
	@docker-compose down --volumes

default:
	@docker-compose exec app bin/console s:c -t2022

bucket1:
	@docker-compose exec app bin/console s:c -a50 -spzu -skgh -satt -spkp -s ifi -s san -s ase -s abs -s trn -s pcr -satg -s 1at -s wtn -s bhw -sulg -spkn -sacp -sase -sopn -sska

wig20:
	@docker-compose exec app bin/console s:c -a50 -sacp -sale -sccc -scdr -scps -sdnp -sjsw -skgh -skty -slpp -smbk -sopl -spco -speo -spge -spgn -spkn -spko -spzu -sspl 

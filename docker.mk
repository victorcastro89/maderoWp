# include .env 
# .PHONY: up down stop prune ps shell wp logs

# test:
# 	@echo "Starting up containers for for ... $2” 

# up:
# 	@echo "Starting up containers for for $(DB_ROOT_PASSWORD)..."

# backup:
# 	@echo "Backuping databases..."
# 	docker-compose exec mariadb sh -c 'exec mysqldump --all-databases -uroot -p"$(DB_ROOT_PASSWORD)"' >./backup_db/Maderos_Backup_`date +%d-%m-%Y-%T`.sql


# query:
# 	docker-compose exec mariadb sh -c 'mysql -u$(DB_USER) -p$(DB_PASSWORD) -h$(DB_HOST) -e "`$1`" $(DB_NAME)'

# %:
# 	@:

include .env

.PHONY: import backup query query-silent query-root check-ready check-live

check_defined = \
    $(strip $(foreach 1,$1, \
        $(call __check_defined,$1,$(strip $(value 2)))))
__check_defined = \
    $(if $(value $1),, \
      $(error Required parameter is missing: $1$(if $2, ($2))))

command = mysqladmin -uroot -p${root_password} -h${host} status &> /dev/null
user ?= $(DB_USER)
password ?= $(DB_PASSWORD)
db ?= $(DB_NAME)
root_password ?= $(DB_ROOT_PASSWORD)
host ?= $(DB_HOST)
max_try ?= 1
wait_seconds ?= 1
delay_seconds ?= 0
ignore ?= ""

default: query

# import:
# 	$(call check_defined, source)
# 	import $(user) $(root_password) $(host) $(db) $(source)

backup:
	@echo "Backuping databases..."
	docker-compose exec mariadb sh -c 'exec mysqldump --all-databases -uroot -p"$(DB_ROOT_PASSWORD)"' >./backup_db/Maderos_Backup_`date +%d-%m-%Y-%T`.sql

query:
	$(call check_defined, query)
	docker-compose exec mariadb sh -c 'mysql -u$(user) -p$(password) -h$(host) -e "$(query)" $(db)'

cli:
	docker-compose exec mariadb sh -c 'mysql -uroot -p$(root_password) -h$(host)  '
	
query-silent:
	$(call check_defined, query)
	docker-compose exec mariadb sh -c 'mysql --silent -u$(user) -p$(password) -h$(host) -e "$(query)" $(db)'

query-root:
	$(call check_defined, query)
	docker-compose exec mariadb sh -c 'mysql -p$(root_password) -h$(host) -e "$(query)" $(db)'

mysql-upgrade:
	docker-compose exec mariadb sh -c 'mysql_upgrade -uroot -p$(root_password) -h$(host)'

mysql-check:
	docker-compose exec mariadb sh -c 'mysqlcheck -uroot -p$(root_password) -h$(host) $(db)'

check-ready:
	docker-compose exec mariadb sh -c 'wait_for "$(command)" "MariaDB" $(host) $(max_try) $(wait_seconds) $(delay_seconds)'

check-live:
	@echo "OK"
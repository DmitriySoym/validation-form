.PHONY: install start stop down terminal

RED := \033[31m
GREEN := \033[32m
YELLOW := \033[33m
BLUE := \033[34m
BOLD := \033[1m
RESET := \033[0m

install:
	@echo "Installing..."
	docker compose build
	docker compose up -d
	@echo -e "$(YELLOW)to start project open: <http://localhost>$(RESET)"

stop:
	@echo "Stopping..."
	docker compose stop

start:
	@echo "Starting..."
	docker compose up -d
	@echo -e "$(YELLOW)to start project open: <http://localhost>$(RESET)"

down:
	@echo "Stopping..."
	docker compose down

terminal:
	@echo "Opening terminal..."
	docker exec -it app_php bash
"# ProjectWork — Team 5

Applicazione web per la **gestione del magazzino** di una polizia locale.  
Permette di catalogare i prodotti in dotazione (vestiario, calzature, dispositivi), gestire le giacenze per posizione fisica (armadio/scaffale), applicare filtri di ricerca avanzati e generare report Excel.

---

## Tecnologie

| Layer | Tecnologia |
|---|---|
| Backend | PHP 8.2 + Apache (dentro Docker) |
| Frontend | HTML + Bootstrap 5.3 + JavaScript vanilla |
| Database | MariaDB 12 (dentro Docker) |
| Orchestrazione | Docker Compose |

---

## Come avviare il progetto

Requisiti: **Docker Desktop** installato e avviato.

```bash
# Clona il repository, poi dalla cartella radice:
docker compose up --build
```

Dopo l'avvio:

| Servizio | URL |
|---|---|
| Frontend (app) | http://localhost |
| Backend (API) | http://localhost:8081/api |
| phpMyAdmin (DB) | http://localhost:8080 |

> Il database viene inizializzato automaticamente al primo avvio con tutte le tabelle e i dati di partenza (`db/dbinit.sql`). I dati persistono nel volume Docker anche dopo `docker compose down`. Per azzerare il DB usare `docker compose down -v`.



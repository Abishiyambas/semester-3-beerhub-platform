# BeerHub - Platform

## 1. Prerequisites 

- [Docker Desktop](https://www.docker.com/products/docker-desktop) (or Docker Engine + Docker Compose)
- Node.js + npm
- .NET SDK 9.0
- PHP & Composer

---

## 2. Laravel – Install & Build (Host)

From the repo root:

```bash
cd beerhub
```

### 2.1. PHP dependencies

```bash
composer install
```

This installs all Laravel backend dependencies.

### 2.2. Frontend dependencies

```bash
npm install
```

### 2.3. Build frontend assets (Vite)

```bash
npm run build
```

This generates files under `public/build/...` that are served in Docker.

---

## 3. Running with Docker

All Docker commands are run from the **repo root**, alongside `docker-compose.yml`.

### 3.1. Build images

```bash
docker compose build
```

### 3.2. Start the stack

```bash
docker compose up
```

or detached (background):

```bash
docker compose up -d
```

Services:

- Laravel: http://localhost:8000
- API: http://localhost:5189
- MySQL: localhost:3306 (inside Docker: host `mysql`, user `root`, password `rootsecret`)

---

## Workflow

1. Install & build Laravel assets once:

   ```bash
   cd beerhub
   composer install
   npm install
   npm run build
   cd ..
   ```

2. Build and start everything:

   ```bash
   docker compose build
   docker compose up
   ```

3. Open:

   - Laravel UI: http://localhost:8000
   - API: http://localhost:5189

4. Shut down and remove when done:

   ```bash
   docker compose down
   ```

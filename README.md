# IntegraHub Project

Este proyecto es un sistema distribuido de gestión de órdenes e inventario diseñado con una arquitectura orientada a eventos y microservicios internos.

## 🚀 Arquitectura y Componentes

El sistema se compone de varios servicios y trabajadores comunicados a través de **RabbitMQ**:

-   **Portal (Frontend/Backend Administrativo)**: Interfaz de usuario para gestionar órdenes y visualizar analíticas.
-   **Orders API**: Servicio núcleo para la creación y procesamiento de órdenes.
-   **RabbitMQ**: Message Broker que maneja la comunicación asíncrona.
-   **PostgreSQL**: Base de datos principal para persistencia de datos.
-   **Workers**:
    -   `orders_worker`: Procesa la lógica de creación de órdenes (reserva de inventario, pagos).
    -   `router_worker`: Implementa el patrón **Message Router** para distribuir eventos de órdenes a notificaciones.
    -   `inventory_inbox_worker`: Importa automáticamente archivos CSV de inventario.
    -   `ops_worker` / `customer_worker`: Manejan las notificaciones para operaciones y clientes respectivamente.

## 🛠️ Requisitos Previos

-   [Docker](https://www.docker.com/get-started)
-   [Docker Compose](https://docs.docker.com/compose/install/)

## 🏁 Instalación y Puesta en Marcha

Sigue estos pasos para levantar todo el entorno localmente:

### 1. Clonar el repositorio
```bash
git clone <url-del-repositorio>
cd /integrahub
```

### 2. Configurar variables de entorno
Asegúrate de tener los archivos `.env` configurados en `services/portal` y `services/orders-api`. Puedes usar los archivos `.env.example` como base.

### 3. Construir y levantar los contenedores
```bash
docker compose build
docker compose up -d
```

### 4. Verificar el estado
Puedes ver los logs para asegurar que los workers y servicios subieron correctamente:
```bash
docker compose logs -f
```

## 🌐 Acceso a los Servicios

Una vez que los contenedores estén corriendo, puedes acceder a:

-   **Portal Web**: [http://localhost:8081](http://localhost:8081)
-   **Orders API (Endpoint base)**: [http://localhost:8081/api](http://localhost:8081/api)
-   **Panel de RabbitMQ**: [http://localhost:15672](http://localhost:15672) (User: `integra_user`, Pass: `integra_pass_123`)

## 🛡️ Resiliencia e Idempotencia

El sistema incluye:
-   **Retries con Dead Letter Queues (DLQ)** para manejo de fallos en el procesamiento de órdenes.
-   **Circuit Breaker** para llamadas a servicios externos (ej. Pagos).
-   **Correlation IDs** para trazabilidad de punta a punta.
-   **Estado de Idempotencia** en la base de datos para evitar doble procesamiento de mensajes.

---
*Hayland Montalvo, Jorge Moncayo, Emilio Albornoz y Rebeca Barrezueta*

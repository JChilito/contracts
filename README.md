# Contract Management API

API REST desarrollada con **Symfony** para la gestión de contratos y la proyección de cuotas de pago utilizando diferentes estrategias financieras.

## Arquitectura y Patrones de Diseño

Este proyecto fue construido siguiendo principios de **Clean Code** y **DDD (Domain-Driven Design)** simplificado.

* **Patrón Strategy:** Implementado para el cálculo de cuotas (`PayPal` vs `PayOnline`). Permite agregar nuevos métodos de pago sin modificar el código existente (Open/Closed Principle).
* **Patrón Factory:** Utilizado para seleccionar dinámicamente la estrategia de pago correcta en tiempo de ejecución.
* **Value Objects:** Para encapsular la lógica de validación y formato de `Money` y `PaymentMethod`.
* **DTOs & Mappers:** Para desacoplar la capa de presentación (Controlador) de la capa de dominio (Entidad), manteniendo los servicios limpios.
* **Separation of Concerns:** Lógica de negocio en Servicios, transformación de datos en Mappers y validación en DTOs.

## Instalación y Configuración

1.  **Clonar el repositorio:**
    ```bash
    git clone [https://github.com/JChilito/contracts.git](https://github.com/JChilito/contracts.git)
    cd contracts
    ```

2.  **Instalar dependencias:**
    ```bash
    composer install
    ```

3.  **Configurar Variables de Entorno:**
    Crear el archivo `.env.dev.local` y configura tu conexión a base de datos:
    ```ini
    # .env.local
    DATABASE_URL="mysql://usuario:password@127.0.0.1:3306/technical_test_db?serverVersion=8.0&charset=utf8mb4"
    ```

4.  **Base de Datos y Migraciones:**
    ```bash
    php bin/console doctrine:database:create
    php bin/console doctrine:migrations:migrate
    ```

5.  **Iniciar el Servidor:**
    ```bash
    symfony server:start
    ```

## Documentación de la API

### 1. Crear Contrato
Registra un nuevo contrato en el sistema.

* **Endpoint:** `POST /api/contracts`
* **Body (JSON):**
    ```json
    {
        "contractNumber": "CNT-2026-001",
        "contractDate": "2026-01-21",
        "totalValue": 5000000,
        "paymentMethod": "paypal"
    }
    ```
* **Nota:** Los métodos de pago válidos son `paypal` y `payonline`.

### 2. Proyectar Cuotas
Calcula las cuotas mensuales basadas en la estrategia de pago del contrato.

* **Endpoint:** `GET /api/contracts/{contractId}/installments/{months}`
* **Ejemplo:** `/api/contracts/1/installments/12`
* **Respuesta (JSON):**
    ```json
    {
        "totalContractValue": "$ 5.000.000,00",
        "totalBalanceInterest": "$ 50.000,04",
        "totalRate": "$ 99.999,96",
        "totalValueWithInterestAndRates": "$ 5.150.000,00",
        "installments": [
            {
                "quotaNumber": 1,
                "expirationDate": "2026-02-21",
                "amountBase": "$ 416.666,67",
                "balanceInterest": "$ 4.166,67",
                "paymentRate": "$ 8.333,33",
                "totalValue": "$ 429.166,67"
            }
        ]
    }
    ```
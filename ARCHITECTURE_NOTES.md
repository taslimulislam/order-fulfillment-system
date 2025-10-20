# ARCHITECTURE_NOTES.md
**Project:** Order Fulfillment System  
**Developer:** Taslimul Islam  
**Date:** 2025-10-20  

---

## 1. Overview  
This system implements a modular, event-driven **Order Fulfillment System** for a multi-vendor commerce platform.  
The main goals were:  
- Ensure **clean architecture** using Service and Repository layers.  
- Maintain **separation of concerns** for scalability and testability.  
- Guarantee **transactional integrity** and **asynchronous processing** using events, listeners, and jobs.  

---

## 2. Architectural Design Decisions  

### a. Layered Structure (Controller → Service → Repository → Model)  
**Reasoning:**  
Following the challenge guidelines, responsibilities were separated across layers:  
- **Controllers** handle only request validation and response formatting.  
- **Services** encapsulate all **business logic** (e.g., order creation, stock validation, event firing).  
- **Repositories** handle **database interaction** via Eloquent ORM, ensuring testability and easy swapping of data sources.  
- **Models & Observers** manage entity-level behaviors like generating unique order numbers.  

**Trade-off:**  
This structure adds a bit of overhead for small projects, but it provides **clean boundaries** and makes the system more **maintainable and scalable** as it grows.

---

### b. Event-Driven Design  
When an order is placed, the system fires an `OrderPlaced` domain event that triggers multiple listeners:  
- `UpdateSellerBalanceListener` updates each seller’s balance.  
- `SendOrderConfirmationListener` simulates an email notification.  
- `AuditTrailListener` logs structured order activity.  

**Reasoning:**  
Using events and listeners decouples business actions from side effects. Each feature can evolve independently.  

**Trade-off:**  
Slightly more complexity and background queue management, but the gain in **modularity and fault isolation** outweighs it.

---

### c. Transactional Safety  
`OrderService::createOrder()` runs inside a **database transaction**.  
If any step (e.g., stock reduction, order creation, or event dispatch) fails, the entire operation rolls back.  

**Reasoning:**  
Guarantees **data consistency** even under concurrency or partial failures.  
Laravel’s `DB::transaction()` provides a clean rollback mechanism.  

**Trade-off:**  
Requires careful handling of async jobs to ensure eventual consistency for background tasks.

---

### d. Asynchronous Jobs & Queues  
The `GenerateInvoiceJob` and queued listeners run in the background, enabling **non-blocking operations** for heavy or delayed tasks like invoice creation or email simulation.  

**Reasoning:**  
Improves API response time and user experience.  

**Trade-off:**  
Requires queue configuration and monitoring, but adds scalability for production use.

---

### e. Security and Role-Based Access  
Used **Laravel Sanctum** for token-based authentication.  
Policies restrict data visibility:  
- Buyers can only view their own orders.  
- Sellers can only access orders that contain their products.  

**Reasoning:**  
Ensures **data isolation** across different user roles while keeping implementation simple.  

---

## 3. Design Patterns Used  

| Pattern | Purpose | Example |
|----------|----------|----------|
| **Repository Pattern** | Abstract database operations | `OrderRepository`, `ProductRepository` |
| **Service Layer Pattern** | Encapsulate business logic | `OrderService` |
| **Observer Pattern** | Automate model-level behavior | `OrderObserver` |
| **Event-Listener Pattern** | Decouple domain changes from side effects | `OrderPlaced`, `UpdateSellerBalanceListener` |
| **Command Pattern** | Encapsulate invoice processing task | `orders:process-invoices` command |
| **Job / Queue Pattern** | Execute long-running tasks asynchronously | `GenerateInvoiceJob` |

---

## 4. Trade-offs and Considerations  

| Area | Decision | Trade-off |
|------|-----------|-----------|
| **Monolithic vs Modular** | Chose modular monolith | Slight setup overhead but easy maintenance |
| **Synchronous vs Asynchronous** | Asynchronous listeners for heavy tasks | Requires queue setup |
| **Strict Layering** | Enforced via DI and repositories | More boilerplate code |
| **Logging and Auditing** | JSON logs under `storage/logs/orders.log` | Simple but not searchable without parsing |

---

## 5. Conclusion  
The system follows **clean architecture**, ensuring clear separation of responsibilities, testability, and maintainability.  
It balances **simplicity** for local testing with **scalability** for production environments through events, jobs, and role-based access.  
This design can easily be extended with additional event listeners, payment gateways, or notification services in the future.

---

**Developer:** *Taslimul Islam*  
**Reviewed:** *2025-10-20*  

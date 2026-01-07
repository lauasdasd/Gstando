# Sistema de Gestión de Préstamos – Empresa Terciarizada (Nuevo Banco del Chaco)

Sistema web desarrollado para una **empresa de terceros** dedicada a la gestión de préstamos vinculados al **Nuevo Banco del Chaco**.  
Permite administrar clientes, préstamos, cuotas y realizar validaciones mediante **APIs externas** para análisis crediticio.

> 🔹 Proyecto de uso real  
> 🔹 Orientado a gestión financiera y análisis de riesgo  
> 🔹 Desarrollado con arquitectura MVC

---

## Descripción del Proyecto

El sistema está diseñado para gestionar de forma integral el ciclo de vida de un préstamo, desde el alta del cliente hasta la finalización del crédito, incorporando controles externos para minimizar riesgos.

Permite:
- Registrar clientes y sus datos personales
- Gestionar préstamos, cuotas y fechas clave
- Validar clientes mediante APIs externas (riesgo crediticio)
- Generar reportes detallados para análisis financiero

---

## Funcionalidades Principales

### Gestión de Clientes
- Alta, baja y modificación de clientes
- Registro de datos personales y financieros
- Validación previa antes de otorgar préstamos

### Gestión de Préstamos
- Registro de préstamos
- Control de cuotas
- Fechas de inicio y finalización
- Seguimiento del estado del crédito

### Validaciones Externas (APIs)
- Consulta a **APIs de riesgo crediticio** (ej. Veraz)
- Integración con **DocuEst**
- Validación automática antes de aprobar préstamos

### Reportes y Análisis
- Reportes por:
  - Líneas de crédito
  - Meses
  - Clientes
  - Rangos etarios
- Visualización clara para toma de decisiones

---

## Arquitectura

El sistema está desarrollado siguiendo el patrón **MVC (Model - View - Controller)**:

- **Models**: lógica de negocio y acceso a datos
- **Controllers**: manejo de solicitudes y flujos
- **Views**: interfaz de usuario
- **Router**: gestión de rutas y endpoints

Esta arquitectura permite:
- Mejor mantenibilidad
- Escalabilidad
- Separación clara de responsabilidades

---

## Tecnologías Utilizadas

### Backend
- **PHP**
- **MySQL**
- Arquitectura **MVC**
- Consumo de **APIs REST**

### Frontend
- **HTML5**
- **CSS3**
- **Bootstrap**

### Librerías y Herramientas
- **DataTables**
- **Bootstrap**
- **APIs externas (riesgo crediticio y validación)**
- **Git & GitHub**

---

## Configuración

Por razones de seguridad, **no se incluyen credenciales reales ni claves de APIs**.

Antes de ejecutar el proyecto es necesario configurar:

- Conexión a la base de datos
- Credenciales de APIs externas
- Variables de entorno

Los archivos de configuración contienen **placeholders**.

---

## Estado del Proyecto

✅ Proyecto finalizado  
✅ Funcional  
✅ Utilizado en un entorno real  

---


## Autor

**Lautaro Varga**  
Técnico Superior en Desarrollo de Software  
📍 Argentina  

🔗 GitHub: https://github.com/lauasdasd

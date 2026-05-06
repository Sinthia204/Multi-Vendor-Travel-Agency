# Project Report

## Project Overview (TravelNest)
TravelNest is a Laravel-based travel booking platform that supports end-to-end customer booking and payment workflows and provides a full admin panel for operational management.

### Key Features
- Public landing page with package browsing and search
- User registration, login, and session-based access
- Package booking flow with coupon application
- Payment initiation, callback verification, and invoice generation
- Admin panel for users, agencies, packages, bookings, and payments
- Reporting for revenue, bookings, coupons, and operational KPIs

### Target Users
- Travelers booking tours and packages
- Admins managing bookings, users, payments, and reports
- Agencies or staff maintaining travel content

### Tech Stack
- Backend: Laravel (PHP)
- Frontend: Blade templates
- Database: MySQL
- Payments: SSLCommerz (bKash, Nagad, Card)

### Architecture and Modules
- Presentation: Blade templates for customer and admin UI
- Application: Controllers, services, and middleware for workflows
- Data: Eloquent models and migrations for core entities
- Integrations: SSLCommerz gateway callbacks and verification
- Core modules: users, agencies, packages, bookings, payments, payment logs, coupons, notifications, reports, and settings

---

# Chapter 6. Project Planning and Scheduling
Planning and scheduling are critical to keep the project aligned with goals, track progress, and reduce delivery risks. This chapter covers planning and scheduling for TravelNest, a Laravel-based travel booking system with a MySQL database and SSLCommerz payment integration. The project duration is three months.

## 6.0 High-Level Schedule (Three Months)
The schedule is broken into four phases with clear milestones to keep delivery on time.

| Phase | Duration | Key Activities | Milestones |
| --- | --- | --- | --- |
| Initiation and Requirements | Weeks 1-2 | Stakeholder alignment, scope definition, requirement validation | Approved scope and requirements baseline |
| System Design | Weeks 3-4 | Architecture design, database schema, report templates, integration plan | Design sign-off |
| Development and Integration | Weeks 5-9 | Core modules, database implementation, reporting, integration testing | Feature-complete build |
| Testing and Deployment | Weeks 10-12 | System testing, UAT, fixes, final deployment | Production release and handover |

## 6.1 Function Point Estimation
Function Point Estimation (FPE) measures the size and complexity of a software application from the user perspective. For TravelNest, FPE evaluates customer booking, payment processing, and admin management functions.

### 6.1.1 Functionality, Input, Output
Table 6.1.1: Functionality, Input, Output

| # | Functionality | Input | Output |
| --- | --- | --- | --- |
| 1 | User Registration and Login | User credentials, profile data | Login confirmation or error message |
| 2 | Package Browsing and Search | Search filters, category, price range | Package list and details |
| 3 | Booking Creation | Package selection, travel date, traveler details | Booking confirmation |
| 4 | Coupon Apply or Remove | Coupon code, booking reference | Discounted total or validation error |
| 5 | Payment Initiation | Payment method, booking reference | Payment session and redirect |
| 6 | Payment Callback and Verification | Gateway callback payload | Payment status and invoice |
| 7 | Admin Package Management | Package data (CRUD) | Save confirmation |
| 8 | Admin Booking Management | Status updates, filters | Updated booking records |
| 9 | Reporting and Analytics | Date range, report type | Revenue and booking reports |
| 10 | Settings and Notifications | Config values, notification actions | Update confirmation |

### 6.1.2 Identify Complexity of Transaction Functions (TF)
Table 6.1.2: Identify Complexity of Transaction Functions (TF)

| # | Transaction Function | Type | FTR | DET | Complexity |
| --- | --- | --- | --- | --- | --- |
| 1 | User Registration and Login | EI | 2 | 6 | Low |
| 2 | Package Browsing and Search | EQ | 3 | 10 | Avg |
| 3 | Booking Creation | EI | 3 | 12 | Avg |
| 4 | Coupon Apply or Remove | EI | 2 | 6 | Avg |
| 5 | Payment Initiation | EI | 3 | 10 | Avg |
| 6 | Payment Callback and Verification | EO | 3 | 8 | Avg |
| 7 | Admin Package Management | EI | 2 | 12 | Avg |
| 8 | Admin Booking Management | EQ | 3 | 12 | Avg |
| 9 | Reporting and Analytics | EO | 3 | 15 | Avg |
| 10 | Settings and Notifications | EI | 2 | 6 | Avg |

### 6.1.3 Identify Complexity of Data Functions (DF)
Table 6.1.3: Identify Complexity of Data Functions (DF)

| # | Data Function | Type | RET | DET | Complexity |
| --- | --- | --- | --- | --- | --- |
| 1 | Users | ILF | 1 | 12 | Low |
| 2 | Agencies | ILF | 1 | 10 | Low |
| 3 | Packages | ILF | 1 | 14 | Low |
| 4 | Bookings | ILF | 1 | 14 | Low |
| 5 | Payments | ILF | 1 | 12 | Low |
| 6 | Payment Logs | ILF | 1 | 10 | Low |
| 7 | Coupons | ILF | 1 | 8 | Low |
| 8 | Admin Notifications | ILF | 1 | 8 | Low |
| 9 | Settings | ILF | 1 | 6 | Low |
| 10 | Hotels | ILF | 1 | 10 | Low |
| 11 | Transports | ILF | 1 | 10 | Low |

### 6.1.4 Unadjusted Function Point Contribution
Table 6.1.4 summarizes how each function contributes to the total unadjusted function points for TravelNest.

### 6.1.5 UFP of Transaction Functions
Table 6.1.5: UFP of TF

| # | Transaction Function | TF | FTR | DET | Complexity | UFP |
| --- | --- | --- | --- | --- | --- | --- |
| 1 | User Registration and Login | EI | 2 | 6 | Low | 3 |
| 2 | Package Browsing and Search | EQ | 3 | 10 | Avg | 4 |
| 3 | Booking Creation | EI | 3 | 12 | Avg | 4 |
| 4 | Coupon Apply or Remove | EI | 2 | 6 | Avg | 4 |
| 5 | Payment Initiation | EI | 3 | 10 | Avg | 4 |
| 6 | Payment Callback and Verification | EO | 3 | 8 | Avg | 5 |
| 7 | Admin Package Management | EI | 2 | 12 | Avg | 4 |
| 8 | Admin Booking Management | EQ | 3 | 12 | Avg | 4 |
| 9 | Reporting and Analytics | EO | 3 | 15 | Avg | 5 |
| 10 | Settings and Notifications | EI | 2 | 6 | Avg | 4 |
|  | Total UFP (TF) |  |  |  |  | 41 |

### 6.1.6 UFP of Data Functions
Table 6.1.6: UFP of DF

| # | Data Function | Type | RET | DET | Complexity | UFP |
| --- | --- | --- | --- | --- | --- | --- |
| 1 | Users | ILF | 1 | 12 | Low | 7 |
| 2 | Agencies | ILF | 1 | 10 | Low | 7 |
| 3 | Packages | ILF | 1 | 14 | Low | 7 |
| 4 | Bookings | ILF | 1 | 14 | Low | 7 |
| 5 | Payments | ILF | 1 | 12 | Low | 7 |
| 6 | Payment Logs | ILF | 1 | 10 | Low | 7 |
| 7 | Coupons | ILF | 1 | 8 | Low | 7 |
| 8 | Admin Notifications | ILF | 1 | 8 | Low | 7 |
| 9 | Settings | ILF | 1 | 6 | Low | 7 |
| 10 | Hotels | ILF | 1 | 10 | Low | 7 |
| 11 | Transports | ILF | 1 | 10 | Low | 7 |
|  | Total UFP (DF) |  |  |  |  | 77 |

Total UFP = UFP (TF) + UFP (DF) = 41 + 77 = 118

### 6.1.7 Calculation of Total Degree of Influence (TDI)
Table 6.1.7: Calculation of TDI

| # | General System Characteristic | Description | DI |
| --- | --- | --- | --- |
| 1 | Data Communications | Communication with payment gateway and inventory-related data | 3 |
| 2 | Distributed Processing | Centralized system with role-based access | 1 |
| 3 | Performance | Real-time booking, payment, and reporting | 4 |
| 4 | Heavily Used Configuration | High daily transaction volume | 3 |
| 5 | Transaction Rate | Frequent transactions throughout the day | 4 |
| 6 | Online Data Entry | Mostly online data entry via forms | 4 |
| 7 | End-User Efficiency | Designed for staff and admin with efficient workflows | 3 |
| 8 | Online Update | Most ILFs updated online | 3 |
| 9 | Complex Processing | Booking management, reporting, and availability checks | 3 |
| 10 | Reusability | Modular design for future enhancements | 3 |
| 11 | Installation Ease | Standard web-based deployment | 2 |
| 12 | Operational Ease | Automated day-start, day-end, and backup | 3 |
| 13 | Multiple Sites | Single location for now | 1 |
| 14 | Facilitate Change | Configurable settings and workflows | 3 |
|  | Total Degree of Influence (TDI) |  | 40 |

### 6.1.8 Final Calculation
Value Adjustment Factor (VAF) = 0.65 + (0.01 x TDI) = 0.65 + 0.4 = 1.05

Adjusted Function Point (AFP) = UFP x VAF = 118 x 1.05 = 123.9

Effort Calculation (Java Productivity = 10.6 hrs/FP):
- Effort in Person Hours = 123.9 x 10.6 = 1313.34 hrs
- Effort in Person Days (8 hrs/day) = 1313.34 / 8 = 164.17 days
- Effort in Man-Months (22 days/month) = 164.17 / 22 = 7.46 months
- With 2 developers: 3.73 months

## 6.2 Schedule Control and Milestones
- Weekly status reviews track progress and resolve blockers early.
- Milestones are reviewed at the end of each phase for scope and quality alignment.
- Risk checks are conducted before moving to testing and deployment.

## 6.3 Cost Table
Cost estimation is the process of predicting the amount of money required to complete a project within a defined scope. This process involves assessing various factors and resources needed for the project, including labor, materials, equipment, and overhead costs. The software cost estimation is mostly based on:

- Personnel cost
- Hardware cost
- Software cost
- Other cost

### 6.3.1 Personnel Cost
Number of total days in a year = 365
Total number of government holidays in a year = 24
Total number of weekly holidays in a year = 52
Total number of working days to develop the project = 365 - (52 + 24) = 289 days
Per month total working days (as specified) = 22 days
Organizational working hours per day = 8 hours
Organizational working hours per month = 22 x 8 = 176 hours

Table 6.3.1: Personnel Salary (recomputed hourly rate)

| Position | Salary/month (BDT) | Salary/hour (BDT) = Salary/month / 176 |
| --- | --- | --- |
| System Analyst | 25,000.00 | 142.05 |
| Planner | 15,000.00 | 85.23 |
| Risk Analyzer | 14,000.00 | 79.55 |
| System Designer | 20,000.00 | 113.64 |
| Coder | 12,000.00 | 68.18 |
| Tester | 12,000.00 | 68.18 |

Working-hour assumptions (kept proportionally the same as the original table):
- Full-duration roles -> 4 months -> 176 x 4 = 704 hours
- Half-duration roles -> 2 months -> 176 x 2 = 352 hours

Table 6.3.2: Personnel Cost Estimation (with working-hour assumptions)

| Designation | Number of Person | Working Hours | Total Salary (BDT) | First Payment (60%) | Remaining 40% | Remaining Payment/Week (12 weeks) | Total Salary (BDT) |
| --- | --- | --- | --- | --- | --- | --- | --- |
| System Analyst | One | 704 | 100,000.00 | 60,000.00 | 40,000.00 | 3,333.33 | 100,000.00 |
| Planner | One | 704 | 60,000.00 | 36,000.00 | 24,000.00 | 2,000.00 | 60,000.00 |
| Risk Analyzer | One | 352 | 28,000.00 | 16,800.00 | 11,200.00 | 933.33 | 28,000.00 |
| System Designer | One | 352 | 40,000.00 | 24,000.00 | 16,000.00 | 1,333.33 | 40,000.00 |
| Coder | One | 352 | 24,000.00 | 14,400.00 | 9,600.00 | 800.00 | 24,000.00 |
| Tester | One | 352 | 24,000.00 | 14,400.00 | 9,600.00 | 800.00 | 24,000.00 |
|  |  |  |  |  |  | Total | 276,000.00 TK |

### 6.3.2 Hardware Cost
Table 6.3.3: Hardware Cost Estimation

| Item | Number | Cost per Unit (BDT) | Total (BDT) |
| --- | --- | --- | --- |
| Developer Laptops | 4 | 20,000 | 80,000 |
| Testing Devices | 4 | 1,000 | 4,000 |
| Server Infrastructure | 1 | 30,000 | 30,000 |
|  |  | Total Hardware Cost | 114,000 TK |

### 6.3.3 Software Cost
Table 6.3.4: Software Cost Estimation

| Item | Cost (BDT) |
| --- | --- |
| Development Tools License | 10,000 |
| Total Software Cost | 10,000 TK |

### 6.3.4 Other Cost
Table 6.3.5: Other Cost Estimation

| Cost Category | Annual Cost (BDT) | Cost of three months (BDT) |
| --- | --- | --- |
| Office Rent | 36,000 | 9,000 |
| Vehicle Rent | 12,000 | 3,000 |
| Utility Bill | 24,000 | 6,000 |
| Others | 10,000 | 2,500 |
|  | Total Cost | 20,500 TK |

### 6.3.5 Accounts Table
Table 6.3.6: Accounts Table (final consolidated)

Salary (Personnel)

| Role | Cost (BDT) |
| --- | --- |
| System Analyst | 100,000.00 |
| Planner | 60,000.00 |
| Risk Analyzer | 28,000.00 |
| System Designer | 40,000.00 |
| Coder | 24,000.00 |
| Tester | 24,000.00 |
| Total Salary | 276,000.00 |

Hardware Cost

| Item | Cost (BDT) |
| --- | --- |
| Developer Laptops | 80,000 |
| Testing Devices | 4,000 |
| Server Infrastructure | 30,000 |
| Total Hardware | 114,000 |

Software Cost

| Item | Cost (BDT) |
| --- | --- |
| Development Tools License | 10,000 |
| Total Software | 10,000 |

Other Cost (3 months)

| Item | Cost (BDT) |
| --- | --- |
| Office Rent | 9,000 |
| Vehicle Rent | 3,000 |
| Utility Bill | 6,000 |
| Others | 2,500 |
| Total Other | 20,500 |

Grand Total (BDT) = Personnel + Hardware + Software + Other
= 276,000 + 114,000 + 10,000 + 20,500
= 420,500.00 TK

## 6.4 Assumptions
- Stakeholders are available for requirement validation and UAT.
- The Laravel + MySQL environment and SSLCommerz sandbox are ready by week 3.
- Changes after design sign-off follow change control to protect schedule.

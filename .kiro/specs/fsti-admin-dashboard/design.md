# Design Document: FSTI Admin Dashboard

## Overview

Dashboard Layanan Administrasi FSTI adalah aplikasi web full-stack yang dibangun dengan Laravel 12 + Inertia.js + Vue 3 + Naive UI. Aplikasi ini memungkinkan admin fakultas untuk membuat dan mengelola form layanan secara dinamis, sementara mahasiswa dan dosen dapat mengisi form tanpa perlu login.

### Tech Stack
- **Backend**: Laravel 12 (PHP 8.2+)
- **Frontend Bridge**: Inertia.js 2.x
- **Frontend**: Vue 3.5+ dengan Composition API
- **UI Library**: Naive UI 2.42+
- **Bundler**: Vite
- **Styling**: Tailwind CSS
- **Database**: MySQL 8.x
- **File Storage**: Laravel Storage (local/S3)

## Architecture

```
┌─────────────────────────────────────────────────────────────────┐
│                         Browser                                  │
├─────────────────────────────────────────────────────────────────┤
│                    Vue 3 + Naive UI                             │
│  ┌──────────────┐  ┌──────────────┐  ┌──────────────┐          │
│  │ Admin Pages  │  │ Public Pages │  │  Components  │          │
│  └──────────────┘  └──────────────┘  └──────────────┘          │
├─────────────────────────────────────────────────────────────────┤
│                      Inertia.js                                  │
├─────────────────────────────────────────────────────────────────┤
│                      Laravel 12                                  │
│  ┌──────────────┐  ┌──────────────┐  ┌──────────────┐          │
│  │ Controllers  │  │   Services   │  │    Models    │          │
│  └──────────────┘  └──────────────┘  └──────────────┘          │
├─────────────────────────────────────────────────────────────────┤
│                       MySQL 8.x                                  │
└─────────────────────────────────────────────────────────────────┘
```

### Directory Structure

```
fsti-dashboard/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Admin/
│   │   │   │   ├── DashboardController.php
│   │   │   │   ├── CategoryController.php
│   │   │   │   ├── FormController.php
│   │   │   │   ├── SubmissionController.php
│   │   │   │   └── SettingController.php
│   │   │   └── Public/
│   │   │       ├── FormController.php
│   │   │       └── TrackingController.php
│   │   ├── Middleware/
│   │   └── Requests/
│   ├── Models/
│   │   ├── User.php
│   │   ├── Category.php
│   │   ├── Form.php
│   │   ├── FormField.php
│   │   ├── Submission.php
│   │   ├── SubmissionData.php
│   │   └── Setting.php
│   ├── Services/
│   │   ├── FormBuilderService.php
│   │   ├── SubmissionService.php
│   │   └── NotificationService.php
│   └── Mail/
├── resources/
│   └── js/
│       ├── Pages/
│       │   ├── Admin/
│       │   │   ├── Dashboard.vue
│       │   │   ├── Categories/
│       │   │   ├── Forms/
│       │   │   ├── Submissions/
│       │   │   └── Settings/
│       │   └── Public/
│       │       ├── FormView.vue
│       │       └── Tracking.vue
│       ├── Components/
│       │   ├── FormBuilder/
│       │   ├── FormRenderer/
│       │   └── Shared/
│       └── Layouts/
│           ├── AdminLayout.vue
│           └── PublicLayout.vue
└── database/
    └── migrations/
```

## Components and Interfaces

### Backend Controllers

#### AdminDashboardController
```php
class DashboardController extends Controller
{
    public function index(): Response
    // Returns dashboard with statistics
    
    public function getStats(Request $request): JsonResponse
    // Returns filtered statistics data
}
```

#### AdminFormController
```php
class FormController extends Controller
{
    public function index(): Response
    // List all forms with pagination
    
    public function create(): Response
    // Show form builder page
    
    public function store(FormRequest $request): RedirectResponse
    // Save new form with schema
    
    public function edit(Form $form): Response
    // Show form builder with existing data
    
    public function update(FormRequest $request, Form $form): RedirectResponse
    // Update form schema
    
    public function destroy(Form $form): RedirectResponse
    // Delete form and related data
    
    public function toggleStatus(Form $form): JsonResponse
    // Toggle active/inactive status
}
```

#### PublicFormController
```php
class FormController extends Controller
{
    public function show(string $slug): Response
    // Display public form for filling
    
    public function submit(SubmitFormRequest $request, Form $form): Response
    // Process form submission
}
```

### Frontend Components

#### FormBuilder Component
```typescript
interface FormBuilderProps {
  initialSchema?: FormSchema;
  onSave: (schema: FormSchema) => void;
}

interface FormSchema {
  title: string;
  description: string;
  categoryId: number;
  fields: FormField[];
  settings: FormSettings;
}

interface FormField {
  id: string;
  type: FieldType;
  label: string;
  placeholder?: string;
  required: boolean;
  options?: FieldOption[];
  validation?: ValidationRule[];
  conditionalLogic?: ConditionalRule;
}

type FieldType = 
  | 'text' 
  | 'textarea' 
  | 'email' 
  | 'number' 
  | 'date' 
  | 'select' 
  | 'radio' 
  | 'checkbox' 
  | 'file';
```

#### FormRenderer Component
```typescript
interface FormRendererProps {
  schema: FormSchema;
  onSubmit: (data: FormData) => void;
  loading?: boolean;
}
```

## Data Models

### Entity Relationship Diagram

```
┌─────────────┐       ┌─────────────┐       ┌─────────────┐
│    User     │       │  Category   │       │    Form     │
├─────────────┤       ├─────────────┤       ├─────────────┤
│ id          │       │ id          │       │ id          │
│ name        │       │ name        │◄──────│ category_id │
│ email       │       │ description │       │ title       │
│ password    │       │ type        │       │ slug        │
│ created_at  │       │ icon        │       │ description │
│ updated_at  │       │ order       │       │ schema      │
└─────────────┘       │ is_active   │       │ is_active   │
                      │ created_at  │       │ created_at  │
                      │ updated_at  │       │ updated_at  │
                      └─────────────┘       └──────┬──────┘
                                                   │
                                                   │
                      ┌─────────────┐              │
                      │ Submission  │◄─────────────┘
                      ├─────────────┤
                      │ id          │
                      │ form_id     │
                      │ tracking_no │
                      │ email       │
                      │ data (JSON) │
                      │ status      │
                      │ notes       │
                      │ created_at  │
                      │ updated_at  │
                      └──────┬──────┘
                             │
                             │
              ┌──────────────┴──────────────┐
              │                             │
    ┌─────────▼─────────┐       ┌──────────▼──────────┐
    │ SubmissionFile    │       │ SubmissionHistory   │
    ├───────────────────┤       ├─────────────────────┤
    │ id                │       │ id                  │
    │ submission_id     │       │ submission_id       │
    │ field_id          │       │ status              │
    │ filename          │       │ notes               │
    │ original_name     │       │ changed_by          │
    │ path              │       │ created_at          │
    │ mime_type         │       └─────────────────────┘
    │ size              │
    │ created_at        │
    └───────────────────┘
```

### Database Schema

```sql
-- categories table
CREATE TABLE categories (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    description TEXT NULL,
    type ENUM('mahasiswa', 'dosen') NOT NULL,
    icon VARCHAR(100) NULL,
    `order` INT DEFAULT 0,
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    UNIQUE KEY unique_name_type (name, type)
);

-- forms table
CREATE TABLE forms (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    category_id BIGINT UNSIGNED NOT NULL,
    title VARCHAR(255) NOT NULL,
    slug VARCHAR(255) NOT NULL UNIQUE,
    description TEXT NULL,
    schema JSON NOT NULL,
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE CASCADE
);

-- submissions table
CREATE TABLE submissions (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    form_id BIGINT UNSIGNED NOT NULL,
    tracking_number VARCHAR(20) NOT NULL UNIQUE,
    email VARCHAR(255) NOT NULL,
    data JSON NOT NULL,
    status ENUM('pending', 'in_review', 'needs_revision', 'approved', 'rejected', 'completed') DEFAULT 'pending',
    admin_notes TEXT NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    FOREIGN KEY (form_id) REFERENCES forms(id) ON DELETE CASCADE,
    INDEX idx_tracking (tracking_number),
    INDEX idx_status (status),
    INDEX idx_email (email)
);

-- submission_files table
CREATE TABLE submission_files (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    submission_id BIGINT UNSIGNED NOT NULL,
    field_id VARCHAR(100) NOT NULL,
    filename VARCHAR(255) NOT NULL,
    original_name VARCHAR(255) NOT NULL,
    path VARCHAR(500) NOT NULL,
    mime_type VARCHAR(100) NOT NULL,
    size BIGINT UNSIGNED NOT NULL,
    created_at TIMESTAMP NULL,
    FOREIGN KEY (submission_id) REFERENCES submissions(id) ON DELETE CASCADE
);

-- submission_histories table
CREATE TABLE submission_histories (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    submission_id BIGINT UNSIGNED NOT NULL,
    status VARCHAR(50) NOT NULL,
    notes TEXT NULL,
    changed_by BIGINT UNSIGNED NULL,
    created_at TIMESTAMP NULL,
    FOREIGN KEY (submission_id) REFERENCES submissions(id) ON DELETE CASCADE,
    FOREIGN KEY (changed_by) REFERENCES users(id) ON DELETE SET NULL
);

-- settings table
CREATE TABLE settings (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `key` VARCHAR(100) NOT NULL UNIQUE,
    value TEXT NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL
);
```


## Correctness Properties

*A property is a characteristic or behavior that should hold true across all valid executions of a system—essentially, a formal statement about what the system should do. Properties serve as the bridge between human-readable specifications and machine-verifiable correctness guarantees.*

### Property 1: Authentication Flow Correctness
*For any* valid admin credentials, authenticating should create a valid session, and *for any* invalid credentials, authentication should fail without creating a session.
**Validates: Requirements 1.2, 1.3, 1.4**

### Property 2: Route Protection
*For any* protected admin route and *for any* unauthenticated request, the system should redirect to the login page.
**Validates: Requirements 1.6**

### Property 3: Category CRUD Integrity
*For any* valid category data, creating a category then retrieving it should return equivalent data. *For any* category update, the updated fields should persist correctly.
**Validates: Requirements 2.2, 2.3, 2.4**

### Property 4: Category Name Uniqueness
*For any* two categories with the same type, their names must be different. Attempting to create a duplicate should fail with validation error.
**Validates: Requirements 2.5**

### Property 5: Form Schema Round-Trip
*For any* valid form schema, saving to database then retrieving should produce an equivalent schema object.
**Validates: Requirements 3.5**

### Property 6: Form Slug Uniqueness
*For any* two forms in the system, their slugs must be unique.
**Validates: Requirements 3.6**

### Property 7: Field Type Support
*For any* supported field type (text, textarea, email, number, date, select, radio, checkbox, file), the form builder should correctly save and the form renderer should correctly display the field.
**Validates: Requirements 3.2, 4.1**

### Property 8: Form Validation Completeness
*For any* form with required fields and *for any* submission missing required field values, validation should fail with specific error messages for each missing field.
**Validates: Requirements 4.2, 4.3**

### Property 9: Submission Tracking Number Uniqueness
*For any* submission created, the tracking number must be unique across all submissions.
**Validates: Requirements 4.4**

### Property 10: Inactive Form Rejection
*For any* inactive form, attempting to view or submit should return an unavailable message.
**Validates: Requirements 4.6**

### Property 11: File Validation Rules
*For any* file upload, if the file type is not in allowed extensions OR file size exceeds limit, the upload should be rejected with appropriate error.
**Validates: Requirements 4.7, 9.2, 9.3**

### Property 12: Submission Status Transitions
*For any* submission status change, a history record should be created with the new status, timestamp, and admin who made the change.
**Validates: Requirements 5.3, 5.4**

### Property 13: Submission Filtering
*For any* filter criteria (form, status, date range), the returned submissions should only include those matching ALL specified criteria.
**Validates: Requirements 5.1**

### Property 14: Tracking Lookup Correctness
*For any* valid tracking number, the system should return the submission with its complete status history ordered by timestamp.
**Validates: Requirements 6.1, 6.2**

### Property 15: Statistics Accuracy
*For any* date range, the dashboard statistics (total, pending, completed counts) should equal the actual count of submissions matching those criteria.
**Validates: Requirements 7.1, 7.2, 7.3, 7.5**

### Property 16: File Cascade Delete
*For any* submission with attached files, deleting the submission should also delete all associated files from storage.
**Validates: Requirements 9.6**

### Property 17: File Authorization
*For any* file download request, the system should verify the requester is an authenticated admin before serving the file.
**Validates: Requirements 9.4**

### Property 18: Email Retry Logic
*For any* failed email send attempt, the system should retry up to 3 times before marking as permanently failed.
**Validates: Requirements 8.5**

## Error Handling

### Backend Error Handling

```php
// Custom Exception Handler
class Handler extends ExceptionHandler
{
    public function render($request, Throwable $e)
    {
        if ($e instanceof ValidationException) {
            return back()->withErrors($e->errors())->withInput();
        }
        
        if ($e instanceof ModelNotFoundException) {
            return Inertia::render('Errors/404');
        }
        
        if ($e instanceof AuthorizationException) {
            return Inertia::render('Errors/403');
        }
        
        return parent::render($request, $e);
    }
}
```

### Frontend Error Handling

```typescript
// Global error handler for Inertia
router.on('error', (errors) => {
  if (errors.response?.status === 422) {
    // Validation errors - handled by form
    return;
  }
  
  if (errors.response?.status === 403) {
    message.error('Anda tidak memiliki akses');
    return;
  }
  
  if (errors.response?.status === 404) {
    message.error('Data tidak ditemukan');
    return;
  }
  
  message.error('Terjadi kesalahan sistem');
});
```

### Error Response Format

```typescript
interface ErrorResponse {
  message: string;
  errors?: Record<string, string[]>;
}
```

## Testing Strategy

### Testing Framework
- **Backend**: PHPUnit + Pest PHP
- **Frontend**: Vitest + Vue Test Utils
- **Property-Based Testing**: Pest dengan custom generators

### Unit Tests
- Model relationships and accessors
- Service class methods
- Validation rules
- Helper functions

### Property-Based Tests
Each correctness property will be implemented as a property-based test using Pest PHP with minimum 100 iterations.

```php
// Example: Property 5 - Form Schema Round-Trip
it('form schema round-trip preserves data', function () {
    $schema = FormSchemaGenerator::generate();
    
    $form = Form::create([
        'category_id' => Category::factory()->create()->id,
        'title' => fake()->sentence(),
        'slug' => fake()->slug(),
        'schema' => $schema,
    ]);
    
    $retrieved = Form::find($form->id);
    
    expect($retrieved->schema)->toEqual($schema);
})->repeat(100);
```

### Integration Tests
- Full authentication flow
- Form submission workflow
- File upload and download
- Email notification sending

### Test Coverage Targets
- Models: 90%+
- Services: 85%+
- Controllers: 80%+
- Vue Components: 70%+

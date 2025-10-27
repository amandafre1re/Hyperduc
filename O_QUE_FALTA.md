# WHAT'S MISSING TO MAKE THE REPOSITORY FUNCTIONAL

## 📊 CURRENT SITUATION

### ✅ COMPLETE (Backend)
1. Database structure (`Voluntec.sql`)
2. Database connection (`app/connection.php`)
3. User CRUD endpoints (create, read, update, delete)
4. Skill management endpoints
5. Interest areas database structure (`add_interest_areas.sql`)

### ⏳ IN DEVELOPMENT
1. Registration form HTML (✅ done) - needs JavaScript
2. PHP file comments (✅ done)

### ❌ MISSING

---

## 🚨 HIGH PRIORITY - BASIC FUNCTIONALITY

### 1. Registration Form JavaScript (`assets/js/usuario/create_user.js`)
**Status:** File doesn't exist (only `novo_usuario.js` exists)
**What's needed:**
- Navigation between steps (Step 1 → Step 2)
- Form validation
- Send Step 1 (personal info) via AJAX
- On interest area change → fetch skills via AJAX
- Multi-select skills
- Final submission (save skills)

**Endpoint mapping:**
- POST `/app/user/create_user.php` - Create user
- GET `/app/skill/get_skills_by_interest.php?areas=design,development` - Fetch skills
- POST `/app/skill/add_user_skills.php` - Save user skills

---

### 2. Setup/Test Database
**Status:** SQL files exist but haven't been executed
**What to do:**
```bash
# 1. Create database
mysql -u root -p < Voluntec.sql

# 2. Add interest areas
mysql -u root -p < add_interest_areas.sql
```

---

### 3. Update Existing JavaScript Files
**Status:** Many files still use old names (Portuguese)
**Files to update:**
- `assets/js/login.js` - Change `usuario_id` → `user_id`
- `assets/js/usuario/usuario_ler.js` - Update field names
- `assets/js/usuario/usuario_update.js` - Update field names
- `assets/js/usuario/usuario_excluir.js` - Check compatibility

---

## 📋 MEDIUM PRIORITY - COMPLETE USER STORIES

### 4. US1.AC2 - Edit Account Settings
**Status:** Backend ready ✅, Frontend missing
**What to do:**
- Add inline edit icons in `registro/update-&-delete_usuario.html`
- Implement inline editing (double-click or icon)
- Call update endpoint

**File:** `registro/update-&-delete_usuario.html` + JavaScript

---

### 5. US1.AC3 - Delete Account
**Status:** Backend ready ✅, Frontend missing
**What to do:**
- Add "Delete Account" button at bottom of page
- Confirmation modal
- Call delete endpoint

**File:** `registro/update-&-delete_usuario.html` + JavaScript

---

### 6. US4.AC2 - Task Assignment with Skills
**Status:** Backend ready ✅, Frontend completely missing
**What to do:**
- Update create/edit task form
- Add autocomplete field for skills
- When selecting user → fetch their skills
- Filter suggestions to show only user's skills

**Files:** Task pages + JavaScript

---

## 🔧 LOW PRIORITY - IMPROVEMENTS

### 7. Update Links in HTMLs
**Status:** File names in English, links may be broken
**What to do:**
- Check all `<a href="...">` in project
- Update paths that changed
- Test navigation

---

### 8. Add More Skills to Database
**Status:** Only example skills exist
**What to do:**
- Add more real skills
- Add more area → skill mappings

---

### 9. Error Handling
**Status:** Basic implementation
**What to do:**
- Add more validations
- Improve error messages
- Add better visual feedback

---

## 🎯 SUMMARY CHECKLIST

### To be 100% functional (do now):
- [ ] Create `assets/js/usuario/create_user.js` file (CRITICAL)
- [ ] Execute `Voluntec.sql` in MySQL
- [ ] Execute `add_interest_areas.sql` in MySQL
- [ ] Update `assets/js/login.js` (session variable)
- [ ] Test user registration end-to-end

### To complete User Stories:
- [ ] Implement account editing (US1.AC2)
- [ ] Implement account deletion (US1.AC3)
- [ ] Implement skill filtering in task assignment (US4.AC2)

### For production:
- [ ] Test all flows
- [ ] Add extra validations
- [ ] Improve UX/UI
- [ ] Configure production server

---

## 🔄 RECOMMENDED IMPLEMENTATION ORDER

1. **CRITICAL:** Create `create_user.js` (registration won't work without it)
2. **CRITICAL:** Execute SQL files in database
3. **CRITICAL:** Update `login.js` (login won't work without it)
4. **IMPORTANT:** Test registration end-to-end
5. **IMPORTANT:** Complete US1.AC2 and AC3
6. **IMPROVEMENT:** Implement US4.AC2
7. **POLISH:** Improvements and adjustments

---

## 📝 IMPORTANT NOTES

1. **Session Variables:** All references to `usuario_id` must be `user_id`
2. **Field Names:** All fields in English (name_user, email_user, etc.)
3. **User-facing Messages:** In Portuguese (for Brazilian end users)
4. **Code Comments:** In English (development standard)
5. **Database:** Name is `Voluntec` (capital V)

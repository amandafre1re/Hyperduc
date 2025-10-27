# Implementation Plan for US1 and US4 - Voluntech

## Overview
This document outlines the implementation plan for User Stories US1 (User Account Management) and US4 (Skill Management).

---

## ✅ Database Changes Completed

### New Database Structure (Voluntec.sql)
- Table `user` replaces `usuario` with updated field names
- New `skill` table for hard and soft skills
- New `user_skill` table linking users to their skills
- New `project`, `project_volunteer`, `project_skill`, `task`, `task_skill`, `task_assignment` tables
- `performance_assessment` and `project_participation_request` tables

---

## ✅ Backend Updates Completed

### Updated Files:
1. **app/connection.php** - Database connection updated to use 'Voluntec'
2. **app/login.php** - Updated to use new table structure and session variable
3. **app/user/create_user.php** - Updated field names and returns user_id
4. **app/user/read_user.php** - Updated to new field names
5. **app/user/update_user.php** - Updated to new field names
6. **app/user/delete_user.php** - Updated to cascade delete skills

### New Endpoints Created:
1. **app/skill/list_skills.php** - List all skills (optionally filtered by type)
2. **app/skill/add_user_skills.php** - Assign skills to user
3. **app/skill/get_user_skills.php** - Get skills for logged-in user

---

## 📋 US1: User Account Management

### US1.AC1 - Registration Page
**Status:** ⏳ **TO IMPLEMENT**

**Requirements:**
- Multi-step registration form
- Step 1: Personal information (name, email, password, birth date, city, state, country)
- Step 2: Skill selection (areas of interest + skills)

**Frontend Implementation Needed:**
- Create multi-step registration form in `registro/novo_usuario.html`
- Implement step navigation
- Add skill selection UI in second step

**Backend:** ✅ Ready (create_user.php updated)

---

### US1.AC2 - Edit Account Settings
**Status:** ⏳ **TO IMPLEMENT**

**Requirements:**
- Access via avatar in header
- Click icon to edit individual fields
- Save changes

**Frontend Implementation Needed:**
- Update `registro/lerAlteraApaga_usuario.html`
- Add edit icons next to each field
- Implement inline editing
- Call update_user.php endpoint

**Backend:** ✅ Ready (update_user.php updated)

---

### US1.AC3 - Delete Account
**Status:** ⏳ **TO IMPLEMENT**

**Requirements:**
- Link at bottom of account settings page
- Confirmation dialog before deletion

**Frontend Implementation Needed:**
- Add "Delete Account" link/button at bottom of settings page
- Add confirmation modal
- Call delete_user.php endpoint

**Backend:** ✅ Ready (delete_user.php updated with cascade delete)

---

## 📋 US4: Skill Management

### US4.AC1 - Skill Selection During Registration
**Status:** ⏳ **TO IMPLEMENT**

**Requirements:**
- User selects interest areas (Design, Development, Data Intelligence, Business Management)
- Only show skills related to selected areas
- Allow multiple skill selection

**Frontend Implementation Needed:**
- Step 2 of registration form
- Interest area selector (checkboxes or multi-select)
- Filter skills dynamically based on interest areas
- Multi-select skill picker
- Send selected skills to add_user_skills.php

**Backend:**
- ✅ list_skills.php - Lists all skills (filter by type if needed)
- ✅ add_user_skills.php - Assigns skills to user
- 🔄 Need to add "area_of_interest" field or mapping table

**Additional Backend Needed:**
Create interest areas mapping:
```sql
CREATE TABLE interest_area (
  id_interest_area INT AUTO_INCREMENT PRIMARY KEY,
  name_area VARCHAR(50) NOT NULL
);

CREATE TABLE interest_area_skill (
  id_interest_area INT,
  id_skill INT,
  FOREIGN KEY (id_interest_area) REFERENCES interest_area(id_interest_area),
  FOREIGN KEY (id_skill) REFERENCES skill(id_skill)
);
```

---

### US4.AC2 - Skill Filtering in Task Assignment
**Status:** ⏳ **TO IMPLEMENT**

**Requirements:**
- When assigning task to user, show placeholder for hard skills
- When typing, show only skills known by selected user

**Frontend Implementation Needed:**
- Add hard skill tags/tags input in task creation form
- Implement autocomplete/search
- Fetch user's skills when user selected
- Filter skills to show only user's skills

**Backend:**
- ✅ get_user_skills.php - Gets user's skills
- ⏳ Add task creation endpoint that links skills to tasks

**Additional Backend Needed:**
- Update task creation to link with task_skill table
- Add endpoint to get user skills for specific user (by user_id, not session)

---

## 🎯 Next Steps

### Priority 1: Complete US1.AC1 (Registration)
1. Create multi-step registration form
2. Add JavaScript for navigation between steps
3. Implement skill selection UI
4. Test end-to-end registration flow

### Priority 2: Complete US4.AC1 (Skill Selection)
1. Add interest areas to database
2. Create interest_area_skill mapping table
3. Populate with sample data
4. Implement frontend filtering logic

### Priority 3: Complete US1.AC2 and AC3
1. Add inline editing to account settings
2. Add delete account button with confirmation
3. Test account update and deletion

### Priority 4: Complete US4.AC2 (Task Assignment Skills)
1. Update task creation/assignment UI
2. Add skill autocomplete
3. Filter skills by selected user
4. Test task assignment flow

---

## 📝 Session Variables Change

⚠️ **Important:** Session variable changed from `usuario_id` to `user_id`

All existing JavaScript files that check for session need to be updated.

---

## 🗄️ Sample Data Needed

Add these records to your database:

```sql
-- Add interest areas
INSERT INTO interest_area (name_area) VALUES 
('Design'),
('Development'),
('Data Intelligence'),
('Business Management');

-- Add sample skills
INSERT INTO skill (name_skill, skill_type) VALUES
('UI/UX Design', 'hard'),
('Figma', 'hard'),
('Photoshop', 'hard'),
('JavaScript', 'hard'),
('PHP', 'hard'),
('Python', 'hard'),
('SQL', 'hard'),
('Data Analysis', 'hard'),
('Communication', 'soft'),
('Teamwork', 'soft'),
('Leadership', 'soft');

-- Link skills to interest areas (example)
INSERT INTO interest_area_skill (id_interest_area, id_skill) VALUES
(1, 1), (1, 2), (1, 3), -- Design skills
(2, 4), (2, 5), (2, 6), -- Development skills
(3, 7), (3, 8), -- Data Intelligence skills
(4, 9), (4, 10), (4, 11); -- Business Management skills
```

# Politiek Gesprek - Testing Checklist

## Pre-Testing Setup

### 1. Verify Database
```sql
-- Check if tables exist
SHOW TABLES LIKE 'politiek_gesprek%';

-- Should return:
-- politiek_gesprek_sessions
-- politiek_gesprek_results
-- politiek_gesprek_rate_limit

-- Check if stemwijzer tables exist
SELECT COUNT(*) FROM stemwijzer_questions WHERE is_active = 1;
SELECT COUNT(*) FROM stemwijzer_parties WHERE is_active = 1;
SELECT COUNT(*) FROM stemwijzer_positions;
```

### 2. Verify API Configuration
```bash
# Check if OpenAI API key is set
echo $OPENAI_API_KEY

# Or test via PHP
php -r "echo getenv('OPENAI_API_KEY') ? 'API key found' : 'API key NOT found';"
```

### 3. Check File Permissions
```bash
# Ensure files are readable
ls -la ajax/politiek-gesprek.php
ls -la controllers/politiek-gesprek.php
ls -la views/politiek-gesprek.php
ls -la includes/ChatGPTAPI.php
```

## Basic Functionality Tests

### Test 1: Page Load
- [ ] Navigate to `/politiek-gesprek`
- [ ] Verify hero section loads with gradient background
- [ ] Check that "Start het Gesprek" button is visible
- [ ] Verify stats show: 20 questions, 14 parties, AI label
- [ ] Check browser console for any errors

**Expected**: Clean page load, no console errors

### Test 2: Start Conversation
- [ ] Click "Start het Gesprek" button
- [ ] Button should show "Bezig met starten..."
- [ ] Welcome screen should hide
- [ ] Chat interface should appear
- [ ] First question should display
- [ ] Progress bar should show 5% (1/20)

**Expected**: Smooth transition to chat, first question appears within 2-3 seconds

### Test 3: Multiple Choice Answer
- [ ] Verify 3 buttons appear (Eens, Neutraal, Oneens)
- [ ] Click one option
- [ ] User message appears on right
- [ ] Loading indicator appears
- [ ] AI responds with next question
- [ ] Progress bar updates to 10% (2/20)

**Expected**: Answer recorded, next question loads within 3-5 seconds

### Test 4: Open-Ended Answer
- [ ] Wait for an open-ended question (AI determines this)
- [ ] Verify textarea appears
- [ ] Type a response (e.g., "Ik vind dat we meer moeten investeren in duurzame energie")
- [ ] Click "Verstuur" button
- [ ] Answer appears as user message
- [ ] AI analyzes and responds

**Expected**: Open answer processed correctly, analysis happens within 5-10 seconds

### Test 5: Complete Conversation
- [ ] Continue answering all 20 questions
- [ ] Monitor progress bar (should reach 100%)
- [ ] After question 20, verify results screen appears
- [ ] Check that top party is displayed
- [ ] Verify percentage match is shown
- [ ] Confirm top 5 parties are listed
- [ ] Read AI analysis (should be detailed and relevant)

**Expected**: Full conversation completes, results are comprehensive and make sense

### Test 6: Results Quality
- [ ] Top party match should be between 40-95%
- [ ] All 5 parties should have different percentages
- [ ] AI analysis should mention specific answers
- [ ] Analysis should be in Dutch
- [ ] No placeholders or empty sections

**Expected**: High-quality, personalized analysis

## Advanced Tests

### Test 7: Rate Limiting
- [ ] Complete one full conversation
- [ ] Immediately try to start another
- [ ] Verify error message: "Je kunt maar één gesprek per uur starten"
- [ ] Wait 1 hour OR manually clear rate limit:
```sql
DELETE FROM politiek_gesprek_rate_limit WHERE ip_address = 'YOUR_IP';
```
- [ ] Verify new conversation can start

**Expected**: Rate limiting prevents abuse

### Test 8: Browser Back/Forward
- [ ] Start conversation
- [ ] Answer 5 questions
- [ ] Click browser back button
- [ ] Check if state is preserved or reset
- [ ] Click forward button

**Expected**: Graceful handling (either preserve or clean reset)

### Test 9: Session Recovery (If User Logged In)
- [ ] Login as a user
- [ ] Start conversation
- [ ] Answer 5 questions
- [ ] Close browser tab
- [ ] Return to `/politiek-gesprek`
- [ ] Check if "resume" option appears

**Expected**: Option to continue previous session

### Test 10: Mobile Responsiveness
- [ ] Open on mobile device or use DevTools mobile view
- [ ] Verify hero section is readable
- [ ] Chat interface should be full width
- [ ] Multiple choice buttons stack vertically
- [ ] Textarea is usable on mobile
- [ ] Results display correctly

**Expected**: Fully responsive on all screen sizes

## Edge Cases

### Test 11: Empty Answers
- [ ] For open-ended question, try submitting empty text
- [ ] Verify validation (should not allow)

**Expected**: Cannot submit empty open-ended answers

### Test 12: Very Long Answers
- [ ] For open-ended question, type 500+ words
- [ ] Submit and verify it processes correctly

**Expected**: AI can handle long responses (may take longer)

### Test 13: Special Characters
- [ ] Try answers with emojis: "Ik vind het 🔥"
- [ ] Try Dutch special characters: "Ik ben het hiermee eens, toch?"
- [ ] Try accents: "Één miljoen euro investeren"

**Expected**: All characters handled correctly

### Test 14: Rapid Clicking
- [ ] During multiple choice, click button multiple times quickly
- [ ] Verify only one answer is recorded

**Expected**: Duplicate submissions prevented

### Test 15: Network Interruption
- [ ] Start conversation
- [ ] Turn off network mid-conversation
- [ ] Try to submit answer
- [ ] Verify error message appears

**Expected**: Graceful error handling, user-friendly message

## Performance Tests

### Test 16: Question Generation Speed
- [ ] Track time for each question (especially 11-20)
- [ ] Question 1-10: ~2-3 seconds
- [ ] Question 11-20: ~4-6 seconds (AI generation)

**Expected**: Reasonable response times

### Test 17: Database Load
- [ ] Run 5 conversations simultaneously (multiple browsers/users)
- [ ] Check database for session records
```sql
SELECT COUNT(*) FROM politiek_gesprek_sessions;
SELECT COUNT(*) FROM politiek_gesprek_results;
```

**Expected**: All sessions recorded without conflicts

### Test 18: API Error Handling
- [ ] Temporarily break API key (change it to invalid)
- [ ] Try to start conversation
- [ ] Verify user sees friendly error message, not raw API error

**Expected**: Graceful degradation

## Data Integrity Tests

### Test 19: Verify Database Records
After completing a conversation:
```sql
-- Check session record
SELECT * FROM politiek_gesprek_sessions ORDER BY started_at DESC LIMIT 1;

-- Verify answers JSON is valid
SELECT 
    session_id,
    JSON_VALID(answers) as answers_valid,
    JSON_VALID(conversation_state) as state_valid
FROM politiek_gesprek_sessions 
ORDER BY started_at DESC LIMIT 1;

-- Check results
SELECT * FROM politiek_gesprek_results ORDER BY created_at DESC LIMIT 1;
```

**Expected**: All JSON fields valid, data complete

### Test 20: Party Matching Accuracy
- [ ] Answer all questions with "Eens" 
- [ ] Note which party scores highest
- [ ] Check if it makes sense based on party positions
- [ ] Repeat with all "Oneens"
- [ ] Results should be different

**Expected**: Match algorithm produces sensible results

## AI Quality Tests

### Test 21: Question Relevance
- [ ] Check if questions 11-20 relate to previous answers
- [ ] Verify follow-up questions make sense
- [ ] Ensure no repeated questions

**Expected**: Adaptive questions show context awareness

### Test 22: Analysis Quality
- [ ] Read final AI analysis
- [ ] Check if it references specific answers
- [ ] Verify it explains why parties match
- [ ] Ensure advice is actionable

**Expected**: High-quality, personalized analysis

### Test 23: Dutch Language Quality
- [ ] All questions should be in proper Dutch
- [ ] AI responses should be grammatically correct
- [ ] No English mixed in (except proper nouns)

**Expected**: Professional Dutch throughout

## Security Tests

### Test 24: SQL Injection
Try injecting SQL in open-ended answers:
- [ ] `'; DROP TABLE politiek_gesprek_sessions; --`
- [ ] Verify it's treated as normal text

**Expected**: All inputs sanitized, no SQL injection possible

### Test 25: XSS Testing
Try script injection:
- [ ] `<script>alert('XSS')</script>`
- [ ] Verify it's escaped in display

**Expected**: All outputs escaped, no script execution

### Test 26: Session Hijacking
- [ ] Start conversation, note session_id
- [ ] Try to use that session_id from another browser
- [ ] Verify appropriate security measures

**Expected**: Sessions properly isolated

## User Experience Tests

### Test 27: Error Messages
- [ ] Trigger various errors intentionally
- [ ] Verify all error messages are in Dutch
- [ ] Check that errors are user-friendly
- [ ] Ensure technical details are hidden

**Expected**: Clear, helpful error messages

### Test 28: Loading States
- [ ] Verify loading indicator appears during processing
- [ ] Check that it disappears when done
- [ ] Ensure users know something is happening

**Expected**: Clear feedback during all operations

### Test 29: Accessibility
- [ ] Test with keyboard only (Tab, Enter, Esc)
- [ ] Verify screen reader compatibility
- [ ] Check color contrast ratios
- [ ] Test with browser zoom at 200%

**Expected**: Accessible to all users

## Post-Testing Cleanup

```sql
-- Clean test data (optional, for development)
TRUNCATE TABLE politiek_gesprek_sessions;
TRUNCATE TABLE politiek_gesprek_results;
TRUNCATE TABLE politiek_gesprek_rate_limit;
```

## Known Issues / Limitations

Document any issues found during testing:

1. **Issue**: [Description]
   - **Impact**: [High/Medium/Low]
   - **Workaround**: [If available]
   - **Fix needed**: [Yes/No]

## Success Criteria

The feature is ready for production when:
- [ ] All basic functionality tests pass
- [ ] No console errors during normal operation
- [ ] API errors handled gracefully
- [ ] Rate limiting works correctly
- [ ] Results are accurate and relevant
- [ ] Mobile experience is smooth
- [ ] Page loads within 2 seconds
- [ ] Conversations complete within 10 minutes
- [ ] AI analysis is high quality and relevant
- [ ] No security vulnerabilities found
- [ ] Database records are clean and valid

## Support Contacts

If issues are found:
1. Check browser console for JavaScript errors
2. Check server logs for PHP errors
3. Review OpenAI API usage/errors
4. Verify database connections
5. Test API key separately

## Next Steps After Testing

Once all tests pass:
1. Deploy to production
2. Monitor API costs closely
3. Collect user feedback
4. Iterate on question quality
5. Optimize performance if needed
6. Consider adding analytics


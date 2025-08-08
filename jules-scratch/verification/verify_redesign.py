from playwright.sync_api import sync_playwright, expect

def run(playwright):
    browser = playwright.chromium.launch()
    page = browser.new_page()
    page.goto("http://localhost")

    # Check for the new hero title
    hero_title = page.locator(".hero-title")
    expect(hero_title).to_have_text("Always-On Connectivity for Your Enterprise")

    # Click the first FAQ question
    first_faq_question = page.locator(".faq-question").first
    first_faq_question.click()

    # Check that the answer is visible
    first_faq_answer = page.locator(".faq-answer").first
    expect(first_faq_answer).to_be_visible()

    page.screenshot(path="jules-scratch/verification/verification.png")
    browser.close()

with sync_playwright() as playwright:
    run(playwright)

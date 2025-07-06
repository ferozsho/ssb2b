<?php
/**
 * Template Name: Contact Us Page
 * A custom page template for the Contact Us form with AJAX submission
 */

get_header();
?>

<div id="primary" class="content-area" style="margin-top: 0;">
    <main id="main" class="site-main">
        <div class="contact-section">
            <h1 class="entry-title"><?php the_title(); ?></h1>

            <div class="contact-container">
                <div class="map-column">
                    <div class="google-map">
                        <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3806.291060361037!2d78.4350577751797!3d17.44121508290379!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3bcb90cadd3753a5%3A0x7a6aef63e7e143c6!2sMedvarsity%20Technologies%20Private%20Limited!5e0!3m2!1sen!2sin!4v1719619280901!5m2!1sen!2sin" width="100%" height="500" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                    </div>
                </div>

                <div class="form-column">
                    <div class="contact-form-wrapper">
                        <h2>GET IN TOUCH</h2>
                        <h3>Send us a message</h3>

                        <div class="contact-form-success" style="display: none;">
                            <div class="success-message">
                                <h3>Thank you for contacting us!</h3>
                                <p>We have received your message and will get back to you shortly.</p>
                                <div class="form-group">
                                    <button type="button" id="submit-another" class="submit-another">Submit Another Message</button>
                                </div>
                            </div>
                        </div>

                        <form id="contact-form" class="contact-form">
                            <div class="form-group">
                                <label for="first-name">First Name</label>
                                <input type="text" id="first-name" name="first-name" class="form-control" placeholder="First Name *" required>
                                <span class="error-message" id="first-name-error"><i class="error-icon">⚠</i> This field is required.</span>
                            </div>

                            <div class="form-group">
                                <label for="last-name">Last Name</label>
                                <input type="text" id="last-name" name="last-name" class="form-control" placeholder="Last Name *" required>
                                <span class="error-message" id="last-name-error"><i class="error-icon">⚠</i> This field is required.</span>
                            </div>

                            <div class="form-group phone-group">
                                <label for="phone">Phone number</label>
                                <div class="phone-input-container">
                                    <div class="country-code-dropdown">
                                        <select id="country-code" name="country-code">
                                            <option value="+1">United States (+1)</option>
                                            <option value="+44">United Kingdom (+44)</option>
                                            <option value="+91" selected>India (+91)</option>
                                            <option value="+61">Australia (+61)</option>
                                            <option value="+86">China (+86)</option>
                                            <option value="+33">France (+33)</option>
                                            <option value="+49">Germany (+49)</option>
                                            <option value="+81">Japan (+81)</option>
                                            <option value="+7">Russia (+7)</option>
                                            <option value="+34">Spain (+34)</option>
                                            <option value="+39">Italy (+39)</option>
                                            <option value="+55">Brazil (+55)</option>
                                            <option value="+1">Canada (+1)</option>
                                            <option value="+353">Ireland (+353)</option>
                                            <option value="+64">New Zealand (+64)</option>
                                            <option value="+27">South Africa (+27)</option>
                                            <option value="+82">South Korea (+82)</option>
                                            <option value="+66">Thailand (+66)</option>
                                            <option value="+971">UAE (+971)</option>
                                            <option value="+52">Mexico (+52)</option>
                                            <option value="+65">Singapore (+65)</option>
                                            <option value="+60">Malaysia (+60)</option>
                                            <option value="+31">Netherlands (+31)</option>
                                            <option value="+41">Switzerland (+41)</option>
                                            <option value="+46">Sweden (+46)</option>
                                            <option value="+47">Norway (+47)</option>
                                            <option value="+45">Denmark (+45)</option>
                                            <option value="+358">Finland (+358)</option>
                                            <option value="+48">Poland (+48)</option>
                                            <option value="+420">Czech Republic (+420)</option>
                                            <option value="+36">Hungary (+36)</option>
                                            <option value="+30">Greece (+30)</option>
                                            <option value="+351">Portugal (+351)</option>
                                            <option value="+62">Indonesia (+62)</option>
                                            <option value="+84">Vietnam (+84)</option>
                                            <option value="+64">New Zealand (+64)</option>
                                            <option value="+90">Turkey (+90)</option>
                                            <option value="+20">Egypt (+20)</option>
                                            <option value="+972">Israel (+972)</option>
                                            <option value="+966">Saudi Arabia (+966)</option>
                                            <option value="+1">Puerto Rico (+1)</option>
                                            <option value="+502">Guatemala (+502)</option>
                                            <option value="+504">Honduras (+504)</option>
                                            <option value="+503">El Salvador (+503)</option>
                                            <option value="+506">Costa Rica (+506)</option>
                                            <option value="+507">Panama (+507)</option>
                                            <option value="+593">Ecuador (+593)</option>
                                            <option value="+51">Peru (+51)</option>
                                            <option value="+591">Bolivia (+591)</option>
                                            <option value="+56">Chile (+56)</option>
                                            <option value="+595">Paraguay (+595)</option>
                                            <option value="+598">Uruguay (+598)</option>
                                            <option value="+58">Venezuela (+58)</option>
                                            <option value="+57">Colombia (+57)</option>
                                            <option value="+92">Pakistan (+92)</option>
                                            <option value="+880">Bangladesh (+880)</option>
                                            <option value="+94">Sri Lanka (+94)</option>
                                            <option value="+977">Nepal (+977)</option>
                                            <option value="+960">Maldives (+960)</option>
                                            <option value="+95">Myanmar (+95)</option>
                                            <option value="+856">Laos (+856)</option>
                                            <option value="+855">Cambodia (+855)</option>
                                            <option value="+63">Philippines (+63)</option>
                                            <option value="+853">Macau (+853)</option>
                                            <option value="+852">Hong Kong (+852)</option>
                                            <option value="+886">Taiwan (+886)</option>
                                            <option value="+98">Iran (+98)</option>
                                            <option value="+964">Iraq (+964)</option>
                                            <option value="+962">Jordan (+962)</option>
                                            <option value="+961">Lebanon (+961)</option>
                                            <option value="+963">Syria (+963)</option>
                                            <option value="+965">Kuwait (+965)</option>
                                            <option value="+968">Oman (+968)</option>
                                            <option value="+974">Qatar (+974)</option>
                                            <option value="+973">Bahrain (+973)</option>
                                            <option value="+967">Yemen (+967)</option>
                                        </select>
                                    </div>
                                    <div class="phone-number-input">
                                        <input type="tel" id="phone" name="phone" class="form-control" placeholder="555 123 4567" required>
                                    </div>
                                </div>
                                <span class="error-message" id="phone-error"><i class="error-icon">⚠</i> This field is required.</span>
                            </div>

                            <div class="form-group">
                                <label for="email">E-mail</label>
                                <input type="email" id="email" name="email" class="form-control" placeholder="E-mail *" required>
                                <span class="error-message" id="email-error"><i class="error-icon">⚠</i> Please enter a valid work email address.</span>
                            </div>

                            <div class="form-group">
                                <label for="message">Message</label>
                                <textarea id="message" name="message" class="form-control" rows="5" placeholder="Message *" required></textarea>
                                <span class="error-message" id="message-error"><i class="error-icon">⚠</i> This field is required.</span>
                            </div>

                            <div class="form-group">
                                <?php wp_nonce_field('contact_form_submit', 'contact_form_nonce'); ?>
                                <button type="submit" id="submit-button" class="submit-button">Submit</button>
                                <div class="form-error-message" style="display: none;"></div>
                                <div class="loading-spinner" style="display: none;">
                                    <span class="spinner"></span>
                                    <span class="submit-text">Submitting...</span>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="contact-info-container">
                <div class="contact-info">
                    <div class="info-box">
                        <h3>Head Office</h3>
                        <p>Road No. 12, NBT Colony, Banjara Hills, Hyderabad, Telangana 500034.</p>
                    </div>

                    <div class="info-box">
                        <h3>Email</h3>
                        <p><a href="mailto:info@enterprisesb2b.com">info@enterprisesb2b.com</a></p>
                    </div>
                </div>
            </div>
        </div>
    </main>
</div>

<?php get_footer(); ?>

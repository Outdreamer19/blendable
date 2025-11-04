<template>
  <MarketingLayout>
    <div class="py-20 md:py-32">
      <div class="max-w-3xl mx-auto px-4 md:px-6">
        <!-- Header -->
        <div class="mb-12">
          <h1 class="text-4xl md:text-5xl font-bold tracking-tight text-slate-900 dark:text-white mb-4">
            Privacy Policy
          </h1>
          <p class="text-slate-600 dark:text-slate-400">
            Last updated: {{ new Date().toLocaleDateString('en-US', { year: 'numeric', month: 'long', day: 'numeric' }) }}
          </p>
        </div>

        <!-- Content -->
        <div class="prose prose-slate dark:prose-invert prose-lg max-w-none">
          <section class="mb-12">
            <h2 class="text-2xl font-semibold text-slate-900 dark:text-white mb-4">1. Introduction</h2>
            <p class="text-slate-600 dark:text-slate-300 leading-relaxed mb-4">
              OmniAI ("we," "our," or "us") is committed to protecting your privacy. This Privacy Policy explains 
              how we collect, use, disclose, and safeguard your information when you use our Service.
            </p>
          </section>

          <section class="mb-12">
            <h2 class="text-2xl font-semibold text-slate-900 dark:text-white mb-4">2. Information We Collect</h2>
            
            <h3 class="text-xl font-semibold text-slate-900 dark:text-white mb-3 mt-6">2.1 Information You Provide</h3>
            <p class="text-slate-600 dark:text-slate-300 leading-relaxed mb-4">
              We collect information you provide directly to us, including:
            </p>
            <ul class="list-disc pl-6 space-y-2 text-slate-600 dark:text-slate-300 mb-4">
              <li>Account information (name, email address, password)</li>
              <li>Profile information and preferences</li>
              <li>Content you create, upload, or share through the Service</li>
              <li>Payment and billing information (processed securely through Stripe)</li>
              <li>Communications you send to us</li>
            </ul>

            <h3 class="text-xl font-semibold text-slate-900 dark:text-white mb-3 mt-6">2.2 Information Automatically Collected</h3>
            <p class="text-slate-600 dark:text-slate-300 leading-relaxed mb-4">
              We automatically collect certain information when you use the Service:
            </p>
            <ul class="list-disc pl-6 space-y-2 text-slate-600 dark:text-slate-300 mb-4">
              <li>Usage data (features used, time spent, interactions)</li>
              <li>Device information (browser type, operating system, IP address)</li>
              <li>Log data (access times, pages viewed, errors)</li>
              <li>Cookies and similar tracking technologies</li>
            </ul>
          </section>

          <section class="mb-12">
            <h2 class="text-2xl font-semibold text-slate-900 dark:text-white mb-4">3. How We Use Your Information</h2>
            <p class="text-slate-600 dark:text-slate-300 leading-relaxed mb-4">
              We use the information we collect to:
            </p>
            <ul class="list-disc pl-6 space-y-2 text-slate-600 dark:text-slate-300 mb-4">
              <li>Provide, maintain, and improve the Service</li>
              <li>Process transactions and send related information</li>
              <li>Send technical notices, updates, and support messages</li>
              <li>Respond to your comments, questions, and requests</li>
              <li>Monitor and analyze usage patterns and trends</li>
              <li>Detect, prevent, and address technical issues and security threats</li>
              <li>Personalize your experience</li>
            </ul>
          </section>

          <section class="mb-12">
            <h2 class="text-2xl font-semibold text-slate-900 dark:text-white mb-4">4. How We Share Your Information</h2>
            <p class="text-slate-600 dark:text-slate-300 leading-relaxed mb-4">
              We do not sell your personal information. We may share your information in the following circumstances:
            </p>
            <ul class="list-disc pl-6 space-y-2 text-slate-600 dark:text-slate-300 mb-4">
              <li><strong>Service Providers:</strong> With third-party vendors who perform services on our behalf</li>
              <li><strong>Business Transfers:</strong> In connection with a merger, acquisition, or sale of assets</li>
              <li><strong>Legal Requirements:</strong> When required by law or to protect our rights</li>
              <li><strong>With Your Consent:</strong> When you explicitly authorize us to share information</li>
            </ul>
          </section>

          <section class="mb-12">
            <h2 class="text-2xl font-semibold text-slate-900 dark:text-white mb-4">5. Data Security</h2>
            <p class="text-slate-600 dark:text-slate-300 leading-relaxed mb-4">
              We implement appropriate technical and organizational measures to protect your personal information. 
              However, no method of transmission over the internet or electronic storage is 100% secure. While we 
              strive to use commercially acceptable means to protect your information, we cannot guarantee absolute security.
            </p>
          </section>

          <section class="mb-12">
            <h2 class="text-2xl font-semibold text-slate-900 dark:text-white mb-4">6. Data Retention</h2>
            <p class="text-slate-600 dark:text-slate-300 leading-relaxed mb-4">
              We retain your personal information for as long as necessary to provide the Service and fulfill the 
              purposes outlined in this Privacy Policy, unless a longer retention period is required or permitted by law.
            </p>
          </section>

          <section class="mb-12">
            <h2 class="text-2xl font-semibold text-slate-900 dark:text-white mb-4">7. Your Rights</h2>
            <p class="text-slate-600 dark:text-slate-300 leading-relaxed mb-4">
              Depending on your location, you may have certain rights regarding your personal information, including:
            </p>
            <ul class="list-disc pl-6 space-y-2 text-slate-600 dark:text-slate-300 mb-4">
              <li>Access to your personal information</li>
              <li>Correction of inaccurate information</li>
              <li>Deletion of your personal information</li>
              <li>Objection to processing of your information</li>
              <li>Data portability</li>
              <li>Withdrawal of consent</li>
            </ul>
            <p class="text-slate-600 dark:text-slate-300 leading-relaxed mb-4">
              To exercise these rights, please contact us through our 
              <Link href="/contact" class="text-indigo-600 dark:text-indigo-400 hover:underline">contact page</Link>.
            </p>
          </section>

          <section class="mb-12">
            <h2 class="text-2xl font-semibold text-slate-900 dark:text-white mb-4">8. Cookies and Tracking Technologies</h2>
            <p class="text-slate-600 dark:text-slate-300 leading-relaxed mb-4">
              We use cookies and similar tracking technologies to track activity on our Service and hold certain 
              information. You can instruct your browser to refuse all cookies or to indicate when a cookie is being 
              sent. However, if you do not accept cookies, you may not be able to use some portions of our Service.
            </p>
          </section>

          <section class="mb-12">
            <h2 class="text-2xl font-semibold text-slate-900 dark:text-white mb-4">9. Children's Privacy</h2>
            <p class="text-slate-600 dark:text-slate-300 leading-relaxed mb-4">
              Our Service is not intended for children under the age of 13. We do not knowingly collect personal 
              information from children under 13. If you are a parent or guardian and believe your child has provided 
              us with personal information, please contact us immediately.
            </p>
          </section>

          <section class="mb-12">
            <h2 class="text-2xl font-semibold text-slate-900 dark:text-white mb-4">10. International Data Transfers</h2>
            <p class="text-slate-600 dark:text-slate-300 leading-relaxed mb-4">
              Your information may be transferred to and maintained on computers located outside of your state, 
              province, country, or other governmental jurisdiction where data protection laws may differ. By using 
              the Service, you consent to the transfer of your information to our facilities and those third parties 
              with whom we share it as described in this Privacy Policy.
            </p>
          </section>

          <section class="mb-12">
            <h2 class="text-2xl font-semibold text-slate-900 dark:text-white mb-4">11. Changes to This Privacy Policy</h2>
            <p class="text-slate-600 dark:text-slate-300 leading-relaxed mb-4">
              We may update our Privacy Policy from time to time. We will notify you of any changes by posting the 
              new Privacy Policy on this page and updating the "Last updated" date. You are advised to review this 
              Privacy Policy periodically for any changes.
            </p>
          </section>

          <section class="mb-12">
            <h2 class="text-2xl font-semibold text-slate-900 dark:text-white mb-4">12. Contact Us</h2>
            <p class="text-slate-600 dark:text-slate-300 leading-relaxed mb-4">
              If you have any questions about this Privacy Policy, please contact us at 
              <Link href="/contact" class="text-indigo-600 dark:text-indigo-400 hover:underline">our contact page</Link>.
            </p>
          </section>
        </div>
      </div>
    </div>
  </MarketingLayout>
</template>

<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3'
import MarketingLayout from '@/Layouts/MarketingLayout.vue'
</script>

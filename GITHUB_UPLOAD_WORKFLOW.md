# 📤 How to Upload Your Project to GitHub

Follow these steps to upload your `crypto-puzzle` project to GitHub.

## Prerequisite: Install Git
If you haven't installed Git, download it from [git-scm.com](https://git-scm.com/downloads) and install it.

## Step 1: Create a Repository on GitHub
1.  Go to [GitHub.com](https://github.com/) and log in.
2.  Click the **+** icon in the top-right corner and select **New repository**.
3.  Name your repository (e.g., `crypto-puzzle`).
4.  Choose **Public** or **Private**.
5.  **Do not** check "Initialize with a README" (since we already have one).
6.  Click **Create repository**.
7.  Copy the URL of your new repository (e.g., `https://github.com/your-username/crypto-puzzle.git`).

## Step 2: Initialize Git Locally
Open your terminal (PowerShell or CMD) in the project folder: `e:\xampp\htdocs\crypto-puzzle-main`

Run the following commands one by one:

```bash
# Initialize a new Git repository
git init

# Add all files to the staging area
git add .

# Commit the files
git commit -m "Initial commit - Added Crypto Puzzle project files"
```

## Step 3: Link to GitHub and Push
Replace `YOUR_REPO_URL` with the URL you copied in Step 1.

```bash
# Link your local repo to the remote GitHub repo
git remote add origin YOUR_REPO_URL

# Rename the default branch to 'main' (if not already)
git branch -M main

# Push your code to GitHub
git push -u origin main
```

## Troubleshooting
- If asked for credentials, sign in with your GitHub account.
- If you get an error about "remote origin already exists", run: `git remote remove origin` and try adding it again.
- If you have large files or unwanted folders (like `pma` or `tmp`), create a `.gitignore` file to exclude them.

---
✅ **Done!** Your project is now live on GitHub.

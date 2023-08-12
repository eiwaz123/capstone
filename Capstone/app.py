from flask import Flask,render_template,request,url_for,flash,redirect,sessions,session
from flask_mysqldb import MySQL

app=Flask(__name__)

app.secret_key='capstone'

#Connection sa Database
app.config['MYSQL_HOST']='db4free.net'
app.config['MYSQL_USER']='sebastian200x'
app.config['MYSQL_PASSWORD']='jds09122128032'
app.config['MYSQL_DB']='dbhofin'
app.config['MYSQL_PORT'] = 3306

mysql=MySQL(app)

@app.route('/')
def Home():
    flash('hello world')
    #pang call nang or view nang mga data
    quer=mysql.connection.cursor()
#pang execute nang database
    quer.execute("SELECT * FROM tbl_userinfo")
    data=quer.fetchall()
    quer.close()
                
    #session
    if "username" in session:
        username=session["username"]
    
        return render_template('home.html',users=data,userS=username)
    
    else:
        return redirect(url_for("login"))
    
    

@app.route('/home2' ,methods=['POST','GET'])
def Home2():
    #pang call nang or view nang mga data
    userid=session["userid"]
    quer=mysql.connection.cursor()
#pang execute nang database
    quer.execute('SELECT * FROM tbl_transaction WHERE user_id=%s',[userid])
    data=quer.fetchall()
    quer.close()
    
    flash('hello world')
    if "username" in session:
        username=session["username"]
    
        return render_template('home2.html',transactdata=data,userS=username)
    
    else:
        return redirect(url_for("login"))
    
    

@app.route('/login' ,methods=['POST','GET'])
def login():
    if (request.method=='POST'):
        username=request.form.get('username')
        password=request.form.get('password')
        sql=mysql.connection.cursor()
        sql.execute('SELECT * FROM tbl_user WHERE username=%s AND password=%s',(username,password))
        record=sql.fetchall()
      #eto yung pang identify kung admin o hinde
        for row in record:
            admin=row[7]
            userid=row[0]
    #########################################
        if record:
            if admin=='yes':
                session["username"]=username
                return redirect(url_for("Home"))
            else:
                
                session["username"]=username
                session["userid"]=userid
                return redirect(url_for("Home2"))
        else:
            flash('MAY MALI SA PASSWORD O EMAIL',category='error')
            
        
    return render_template("login.html")

@app.route('/signup', methods=['POST','GET'])
def signup():
    if (request.method=='POST'):
        email=request.form.get('email')
        username=request.form.get('username')
        givenname=request.form.get('givenname')
        middlename=request.form.get('middlename')
        lastname=request.form.get('lastname')
        password=request.form.get('password')
        isadmin='no'
        # if user = meron na 
        if len(email)<3:
            flash('email is too short', category='error')
        elif len(username)<3:
            flash('username is too short' , category='error')
        elif len(givenname)<1:
            flash('given name is too short' , category='error')
        elif len(middlename)<1:
            flash('middlename is too short' , category='error')
        elif len(lastname)<1:
            flash('last name is too short' , category='error')
        elif len(password)<4:
            flash('more than 4 characters is allowed')
        else:
            sql=mysql.connection.cursor()
            
            sql.execute("INSERT INTO tbl_user(email,username,password,given_name,middle_name,last_name,is_admin) VALUES(%s,%s,%s,%s,%s,%s,%s)",(email,username,password,givenname,middlename,lastname,isadmin))
            mysql.connection.commit()
            sql.execute('SELECT * FROM tbl_user WHERE username=%s',[username])   
            data=sql.fetchall()
            for row in data:
                userid=row[0]
                emails=row[1]
            sql.execute("INSERT INTO tbl_userinfo(user_id,email) VALUES(%s,%s)",(userid,emails))
            mysql.connection.commit()
            sql.close()
            return redirect(url_for("login"))
      

    return render_template("signup.html")

@app.route('/logout')
def logout():
    session.pop('username',None)
    return redirect(url_for("login"))


@app.route('/delete/<string:id_data>', methods= ['GET','POST'])
def delete(id_data):
    flash("Record Has Been Deleted Successfully")
    sql = mysql.connection.cursor()
    sql.execute("DELETE FROM tbl_userinfo WHERE id=%s", (id_data,))
    mysql.connection.commit()
    return redirect(url_for('index'))

@app.route('/update',methods=['POST','GET'])
def update():
    if request.method=='POST':
     
         id_data=request.form['id']
         gender=request.form['gender']
         email=request.form['email']
         idno=request.form['idno']
         blk=request.form['blk']
         lot=request.form['lot']
         homelot=request.form['homelot']
         openspace=request.form['openspace']
         share=request.form['share']
         principal=request.form['principal']
         MRIno=request.form['MRI']
         sql=mysql.connection.cursor()
         total=int(share)+int(principal)+int(MRIno)
         sql.execute("UPDATE tbl_userinfo SET gender=%s, email=%s, id_no=%s,blk_no=%s,lot_no=%s,homelot_area=%s,open_space=%s,sharein_loan=%s,principal_interest=%s,MRI=%s,Total=%s WHERE userinfo_id=%s", (gender,email,idno,blk,lot,homelot,openspace,share,principal,MRIno,total, id_data))
         mysql.connection.commit()
         
         flash("NA UPDATE ANG IYONG INFO")
         return redirect(url_for('Home'))

@app.route('/payment/<int:id>', methods=['POST','GET'])
def payment(id):
    if (request.method=='POST'):
        amount=request.form.get('amount')
        transactid=id
        option=request.form.get('option')
        
        sql=mysql.connection.cursor()
        sql.execute('SELECT * FROM tbl_transaction WHERE transac_id=%s',[transactid])
        mysql.connection.commit()
        data=sql.fetchall()
        for row in data:
            debt=row[2]
            totalamt=int(debt)-int(amount)
            
            sql.execute("UPDATE tbl_transaction SET balance_debt=%s, transac_type=%s ,amount=%s WHERE transac_id=%s", (totalamt,option,amount, transactid))
            mysql.connection.commit()
            sql.close()
            return redirect(url_for("Home2"))
    else:
        return render_template("payment.html")
    
    #session
    if "username" in session:
        username=session["username"]
    
        return render_template('payment.html',users=data,userS=username)
    
    else:
        return redirect(url_for("login"))
    

if __name__=='__main__':
    app.run(debug=True)
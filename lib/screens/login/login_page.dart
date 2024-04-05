import 'dart:async';
import 'dart:convert';
import 'package:flutter/material.dart';
import 'package:http/http.dart' as http;
import 'package:school_management/screens/home_screen.dart';

import '../api/api_urls.dart';


class LoginPage extends StatelessWidget {
  const LoginPage({Key? key}) : super(key: key);

  @override
  Widget build(BuildContext context) {
    return _LoginPageBody();
  }
}

class _LoginPageBody extends StatefulWidget {
  const _LoginPageBody({Key? key}) : super(key: key);

  @override
  __LoginPageBodyState createState() => __LoginPageBodyState();
}

class __LoginPageBodyState extends State<_LoginPageBody> {
  late Timer _animationTimer;
  int activeIndex = 0;
  final TextEditingController _emailController = TextEditingController();
  final TextEditingController _passwordController = TextEditingController();

  @override
  void initState() {
    super.initState();
    _startAnimation();
  }

  void _startAnimation() {
    _animationTimer = Timer.periodic(Duration(seconds: 2), (timer) {
      setState(() {
        activeIndex = (activeIndex + 1) % 5;
      });
    });
  }
  @override
  void dispose() {
    _animationTimer.cancel();
    super.dispose();
  }


  void _login(BuildContext context) async {
    String email = _emailController.text;
    String password = _passwordController.text;

    final Uri url = ApiUrls.loginUrl;

    // Validate email and password format
    if (!_isEmailValid(email)) {
      _showLoginFailedDialog(context, 'Please enter a valid email address.');
      return;
    }


    try {
      final response = await http.post(
        url,
        body: {'email': email, 'password': password},
      );

      print('Login response status code: ${response.statusCode}');
      print('Login response body: ${response.body}');

      if (response.statusCode == 200) {
        // Parse the response body
        Map<String, dynamic> data = json.decode(response.body);

        // Check if login was successful
        String message = data['message'];
        if (message == 'Loggedin Successfully') {
          // Check validity
          bool isValid = data['validity'] ?? false;
          if (!isValid) {
            _showLoginFailedDialog(context, 'Your account is not valid.');
            return;
          }

          _navigateBasedOnRole(context, data);
        } else {
          _showLoginFailedDialog(context, 'Invalid email or password. Please try again.');
        }
      } else {
        _showLoginFailedDialog(context, 'Failed to connect to the server.');
      }
    } catch (e) {
      print('Error: $e');
      _showLoginFailedDialog(context, 'An error occurred. Please try again later.');
    }
  }

  bool _isEmailValid(String email) {
    // Add your email validation logic here
    return email.isNotEmpty && email.contains('@');
  }



  void _navigateBasedOnRole(BuildContext context, Map<String, dynamic> userData) {
    bool isValid = userData['validity'] ?? false;

    if (!isValid) {
      _showLoginFailedDialog(context, 'Your account is not valid.');
      return;
    }

    Navigator.pushReplacement(
      context,
      MaterialPageRoute(builder: (context) => MyHomePage(userData: userData)),
    );
  }

  void _showLoginFailedDialog(BuildContext context, String message) {
    showDialog(
      context: context,
      builder: (BuildContext context) {
        return AlertDialog(
          title: Text('Login Failed'),
          content: Text(message),
          actions: <Widget>[
            TextButton(
              onPressed: () => Navigator.of(context).pop(),
              child: Text('OK'),
            ),
          ],
        );
      },
    );
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      body: SingleChildScrollView(
        child: Padding(
          padding: EdgeInsets.all(20.0),
          child: Column(
            children: [
              SizedBox(height: 50),
              SizedBox(
                height: 350,
                child: AnimatedSwitcher(
                  duration: Duration(seconds: 1),
                  child: _buildImage(activeIndex),
                ),
              ),
              SizedBox(height: 40),
              TextField(
                controller: _emailController,
                cursorColor: Colors.black,
                decoration: InputDecoration(
                  labelText: 'Email',
                  hintText: 'Your e-mail',
                  prefixIcon: Icon(Icons.email, color: Colors.black, size: 18),
                  enabledBorder: OutlineInputBorder(
                    borderSide: BorderSide(color: Colors.grey.shade200, width: 2),
                    borderRadius: BorderRadius.circular(10.0),
                  ),
                  focusedBorder: OutlineInputBorder(
                    borderSide: BorderSide(color: Colors.black, width: 1.5),
                    borderRadius: BorderRadius.circular(10.0),
                  ),
                ),
              ),
              SizedBox(height: 20),
              TextField(
                controller: _passwordController,
                cursorColor: Colors.black,
                obscureText: true,
                decoration: InputDecoration(
                  labelText: 'Password',
                  hintText: 'Password',
                  prefixIcon: Icon(Icons.lock, color: Colors.black, size: 18),
                  enabledBorder: OutlineInputBorder(
                    borderSide: BorderSide(color: Colors.grey.shade200, width: 2),
                    borderRadius: BorderRadius.circular(10.0),
                  ),
                  focusedBorder: OutlineInputBorder(
                    borderSide: BorderSide(color: Colors.black, width: 1.5),
                    borderRadius: BorderRadius.circular(10.0),
                  ),
                ),
              ),
              Row(
                mainAxisAlignment: MainAxisAlignment.end,
                children: [
                  TextButton(
                    onPressed: () {},
                    child: Text(
                      'Forgot Password?',
                      style: TextStyle(color: Colors.black, fontSize: 14.0, fontWeight: FontWeight.w400),
                    ),
                  )
                ],
              ),
              SizedBox(height: 30),
              MaterialButton(
                onPressed: () {
                  _login(context);
                },
                height: 45,
                color: Colors.black,
                child: Text(
                  "Login",
                  style: TextStyle(color: Colors.white, fontSize: 16.0),
                ),
                padding: EdgeInsets.symmetric(vertical: 10, horizontal: 50),
                shape: RoundedRectangleBorder(
                  borderRadius: BorderRadius.circular(10.0),
                ),
              ),
              SizedBox(height: 30),
              Row(
                mainAxisAlignment: MainAxisAlignment.center,
                children: [
                  Text(
                    'Don\'t have an account?',
                    style: TextStyle(color: Colors.grey.shade600, fontSize: 14.0, fontWeight: FontWeight.w400),
                  ),
                  TextButton(
                    onPressed: () {},
                    child: Text(
                      'Register',
                      style: TextStyle(color: Colors.blue, fontSize: 14.0, fontWeight: FontWeight.w400),
                    ),
                  )
                ],
              ),
            ],
          ),
        ),
      ),
    );
  }

  Widget _buildImage(int index) {
    switch (index) {
      case 0:
        return Image.asset(
          'assets/images/png/learn.png',
          height: 400,
          key: ValueKey(0),
        );
      case 1:
        return Image.asset(
          'assets/images/png/student.png',
          height: 400,
          key: ValueKey(1),
        );
      case 2:
        return Image.asset(
          'assets/images/png/zoomimage.png',
          height: 400,
          key: ValueKey(2),
        );
      case 3:
        return Image.asset(
          'assets/images/png/online.png',
          height: 400,
          key: ValueKey(3),
        );
      case 4:
        return Image.asset(
          'assets/images/png/course.png',
          height: 400,
          key: ValueKey(4),
        );
      default:
        return Container();
    }
  }
}
